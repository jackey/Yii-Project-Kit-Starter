<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 11/11/15
 * Time: 6:48 PM
 */

namespace Sucel\Service\Dao;

use Sucel\Common\Includes\Database\CDao;

class TicketDao extends CDao {

    const STATUS_AVAILABLE = 1; // 正常
    const STATUS_SCANED = 2; // 被扫描过
    const STATUS_EXPIRED = 3; // 已过期

    public function tableName() {
        return 't_ticket';
    }

    public function primaryKey() {
        return 'Fid';
    }

    /**
     * @param string $class
     * @return TicketDao
     */
    public static function model($class=__CLASS__) {
        return parent::model($class);
    }

}