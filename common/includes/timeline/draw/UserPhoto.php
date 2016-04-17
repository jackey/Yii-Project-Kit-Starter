<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 19/11/15
 * Time: 11:51 AM
 */

namespace Sucel\Common\Includes\Timeline\Draw;

use Sucel\Service\Dao\TimeLineDao;

class UserPhoto extends CDraw{

    public function action() {
        return TimeLineDao::ACTION_POST_PHOTO;
    }

    public function from(){
        return TimeLineDao::FROM_USER;
    }

    public function type() {
        return TimeLineDao::TYPE_PHOTO;
    }

    /**
     *
     * @param TimeLineDao $timeLineDao
     * @return string
     */
    public function draw($timeLineDao) {
        return '';
    }
}