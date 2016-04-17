<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/2/15
 * Time: 3:44 PM
 */

namespace Sucel\Common\Includes\Database;

use Sucel\Common\Includes\Logger;
use Sucel\Common\Includes\UniqueID;

class CDao extends \CActiveRecord{

    protected $isUpdate = false;

    static $_dbConfig;
    static $_tableConfig;

    private $_isSelect;

    public function __construct($scenario='insert') {

        if (empty(CDao::$_dbConfig)) {
            self::$_dbConfig = dbConfig();
            self::$_tableConfig = getParam(CDao::$_dbConfig['tables'], $this->tableName());
        }

        return parent::__construct($scenario);
    }

    public function _selectServer($dbConfig) {
        $useSlave = !empty($dbConfig['slaves']);

        if (!$useSlave) {
            $slaves = array(
                $dbConfig['master']
            );
        }
        else $slaves = $dbConfig['slaves'];

        // TODO:: Slave 选择算法
        $slave = $slaves[0]; // 暂时选择第一个

        if (empty($slave['charset'])) $slave['charset'] = getParam($dbConfig, 'charset', 'utf8mb4');
        if (empty($slave['password'])) $slave['password'] = getParam($dbConfig, 'password');
        if (empty($slave['user'])) $slave['user'] = getParam($dbConfig, 'user');
        if (empty($slave['port'])) $slave['port'] = getParam($dbConfig, 'port');

        return $slave;
    }

    /**
     * 获取表所属数据库链接
     * @return \CDbConnection|void
     * @throws \CDbException
     */
    public function getDbConnection() {
        $tableConfig = CDao::$_tableConfig;
        $dbName = getParam($tableConfig, 'db');
        if (!$dbName) throw new \CDbException("表没有配置 (参考config/DB.php)", 500);

        if ($this->_isSelect) return CConnection::selectSlaveConnection($dbName);

        return CConnection::selectMasterConnection($dbName);
    }

    /**
     * 获取分表后真实表名
     * @param $sourceTableName
     * @param $mk
     * @param $value
     * @param $type
     * @param $params
     * @return string
     */
    public function getRealTableName($sourceTableName, $value, $type, $params) {
        if ($type == 'md5') {
            $pos = getParam($params, 'table_pos'); // 默认取 md5 前6位
            $key = substr(substr(md5($value),8, 16), 0, $pos);
            $realTable = sprintf("%s_%s", $sourceTableName, $key);
        }
        else if ($type == 'date') {
            $format = getParam($params, 'format'); // 默认年月分表
            if (!is_numeric($value)) $value = strtotime($value);
            $date = date($format, $value);
            $realTable = sprintf('%s_%s', $sourceTableName, $date);
        }

        $this->autoCreateTableFromSourceTable($sourceTableName, $realTable);

        return $realTable;
    }

    /**
     * 查找表是否已创建，如果没有 则按照$sourceTable 复制一样的结构
     * @param $sourceTable
     * @param $destTable
     */
    public function autoCreateTableFromSourceTable($sourceTable, $destTable) {
        $sql = 'SHOW TABLES LIKE "'.$destTable.'"';
        $command = $this->getDbConnection()->createCommand();
        $command->setText($sql);
        $rows = $command->queryAll();
        if (count($rows) <= 0) {
            $createCommand = $this->getDbConnection()->createCommand();
            $createCommand->setText('CREATE TABLE '. $destTable.' LIKE '. $sourceTable);
            $createCommand->execute();
        }
    }

    /**
     * 获取表的所有分表
     * @return array()
     */
    public  function getSameTables() {
        $command = $this->getDbConnection()->createCommand();
        $tableName = $this->tableName();
        $command->setText("SHOW TABLES LIKE '${tableName}%'");
        $rows = $command->queryAll();
        $tables = array();
        foreach ($rows as $row) {
            $tables[] = getParam(array_values($row), 0);
        }

        return $tables;
    }

    public function query($criteria, $all = false) {

        $this->_isSelect = true;

        $this->beforeFind();
        $this->applyScopes($criteria);

        // 分表定位
        preg_match_all('([\w]+\s*=:\s*[\w]+)', $criteria->condition, $matches);
        if ($matches) $conditions = getParam($matches, 0);

        $tableName = $this->tableName();

        $tableConfig = self::$_tableConfig;
        $mk = getParam($tableConfig, 'mk', '');
        $type = getParam($tableConfig, 'type');
        // 查询表
        $tables = array();

        // 分表
        if ($mk && $type) {
            $isPositionedInTable = false;
            foreach ($conditions as $cond) {
                // 去除空格
                $cond = str_replace(" ", "" ,$cond);
                // 根据分表键来查询 - 直接定位到具体表
                if (strpos($cond, $mk) !== false
                    && strpos($cond, "{$mk}=") !== false) {
                    $value = getParam($criteria->params ,str_replace("{$mk}=", '', $cond));
                    $tables[] = $this->getRealTableName($this->tableName(), $value, $type, $tableConfig);
                    $isPositionedInTable = true;
                    break;
                }
            }
            if (!$isPositionedInTable) {
                $tables = $this->getSameTables();
            }
        }
        // 未分表
        else {
            $tables[] = $tableName;
        }
        // union join
        $sql = array();
        foreach ($tables as $table){
            $command = $this->getCommandBuilder()->createFindCommand($table, $criteria, $this->getTableAlias());

            $sql[] = $command->getText();
        }
        $sql = implode('union all', $sql);

        Logger::db()->info($sql. "  params: ". mergeArrayToPrint($criteria->params));

        if(empty($criteria->with))
        {
            if(!$all)
                $criteria->limit=1;

            $command = $this->getDbConnection()->createCommand();
            $command->setText($sql);

            foreach ($criteria->params as $key => $value) {
                $command->bindParam($key, $value);
            }

            return $all ? $this->populateRecords($command->queryAll(), true, $criteria->index) : $this->populateRecord($command->queryRow());
        }
        else
        {
            $finder=$this->getActiveFinder($criteria->with);
            return $finder->query($criteria,$all);
        }
    }

    public function insert($attributes=null)
    {
        $this->_isSelect = false;
        if(!$this->getIsNewRecord()) {
            throw new \CDbException(\Yii::t('yii','The active record cannot be inserted to database because it is not new.'));
        }
        if($this->beforeSave())
        {
            \Yii::trace(get_class($this).'.insert()','system.db.ar.CActiveRecord');
            $builder=$this->getCommandBuilder();

            $tableName = $this->tableName();

            // 手动的给予一个自动增长ID
            $this->setPrimaryKey(UniqueID::getAutoIncrementID());
            $tableConfig = self::$_tableConfig;
            $mk = getParam($tableConfig, 'mk');
            $type = getParam($tableConfig, 'type');
            // 分表
            $realTable = $tableName;
            if ($mk && $type) {
                $value = $this->getAttribute($mk);
                $realTable = $this->getRealTableName($tableName, $value, $type, $tableConfig);
            }

            $table=$this->getMetaData()->tableSchema;
            $command=$builder->createInsertCommand($realTable ,$this->getAttributes($attributes));

            Logger::db()->info($command->getText() ."\r\n Model: \r\n". mergeArrayToPrint($this->getAttributes()));

            if($command->execute())
            {
                $primaryKey=$table->primaryKey;
                if($table->sequenceName!==null)
                {
                    if(is_string($primaryKey) && $this->$primaryKey===null)
                        $this->$primaryKey=$builder->getLastInsertID($table);
                    elseif(is_array($primaryKey))
                    {
                        foreach($primaryKey as $pk)
                        {
                            if($this->$pk===null)
                            {
                                $this->$pk=$builder->getLastInsertID($table);
                                break;
                            }
                        }
                    }
                }
                $this->_pk=$this->getPrimaryKey();
                $this->afterSave();
                $this->setIsNewRecord(false);
                $this->setScenario('update');
                return true;
            }
        }
        return false;
    }

    public function updateByPk($pk, $attributes,$condition='',$params=array())
    {
        \Yii::trace(get_class($this).'.updateByPk()','system.db.ar.CActiveRecord');
        $builder=$this->getCommandBuilder();

        $table=$this->getTableSchema();
        $tableConfig = self::$_tableConfig;

        $mk = getParam($tableConfig, 'mk');
        $type = getParam($tableConfig, 'type');
        $value = $this->getAttribute($mk);
        $realTable = $table;
        if ($mk && $type) {
            $realTable = $this->getRealTableName($table, $value, $type, $tableConfig);
        }

        $criteria=$builder->createPkCriteria($realTable ,$pk ,$condition,$params);
        $command=$builder->createUpdateCommand($realTable ,$attributes ,$criteria);
        return $command->execute();
    }

    public function deleteByPk($pk,$condition='',$params=array())
    {
        \Yii::trace(get_class($this).'.deleteByPk()','system.db.ar.CActiveRecord');

        // 删除所有分表
        $table = $this->getTableSchema();
        $tableConfig = self::$_tableConfig;
        $mk = getParam($tableConfig, 'mk');
        $type = getParam($tableConfig, 'type');
        // 分表
        $tables = array($table);
        if ($mk && $type) {
            $tables = $this->getSameTables();
        }

        foreach ($tables as $realTable) {
            $builder=$this->getCommandBuilder();
            $criteria=$builder->createPkCriteria($realTable ,$pk ,$condition ,$params);
            $command=$builder->createDeleteCommand($realTable ,$criteria);
            $command->execute();
        }
    }

    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->isUpdate = false;
        }
        else {
            $this->isUpdate;
        }

        return parent::beforeSave();
    }

    public function updateAll($attributes,$condition='',$params=array()) {
        throw new \CDbException('方法被禁用', 500);
    }

    public function updateCounters($counters,$condition='',$params=array()) {
        throw new \CDbException('方法被禁用', 500);
    }

    public function deleteAll($condition='',$params=array()) {
        throw new \CDbException('方法被禁用', 500);
    }

    public function deleteAllByAttributes($attributes,$condition='',$params=array()) {
        throw new \CDbException('方法被禁用', 500);
    }
}