<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/3/15
 * Time: 4:36 PM
 */

namespace Sucel\Common\Includes;

use Sucel\Common\Includes\Database\CConnection;

class UniqueID {

    /**
     * 获取字段增长ID
     */
    public static function getAutoIncrementID() {
        $tableConfig = dbConfig()['tables']['t_unique_id'];
        $master = CConnection::selectMasterConnection($tableConfig['db']);
        $sql = 'REPLACE t_unique_id VALUES (NULL, "a")';
        $command = $master->createCommand()->setText($sql);
        $command->execute();
        return $master->getLastInsertID();
    }
}