<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 19/11/15
 * Time: 11:51 AM
 */

namespace Sucel\Common\Includes\Timeline\Draw;

use Sucel\Common\Includes\Timeline\TimeLineTemplate;
use Sucel\Service\Dao\TimeLineDao;
use Sucel\Service\Model\Group\PhotoModel;

/**
 * 群组发布成员发布照片的 Timeline
 * @package Sucel\Common\Includes\Timeline\Draw
 */
class GroupPhoto extends CDraw{

    public function action() {
        return TimeLineDao::ACTION_POST_PHOTO;
    }

    public function from(){
        return TimeLineDao::FROM_GROUP;
    }

    public function type() {
        return TimeLineDao::TYPE_PHOTO;
    }

    /**
     * @param TimeLineDao $timeLineDao
     * @return string
     */
    public function draw($timeLineDao) {
        $pid = $timeLineDao->Ffrom_id;

        $photos = PhotoModel::loadPhotoListByPhotoIds(array($pid));

        $photo =  array_shift($photos);
        if ($photo) {
            $template = new TimeLineTemplate();
            $template->comments = $photo['comments'];
            $template->photo = array(
                array(
                    'url' => $photo['photo'],
                    'pid' => $photo['pid'],
                    'stat' => array(
                        'like' => 10,
                        'comment' => 10
                    )
                )
            );
            $template->message = $photo['desc'];
            $template->time = timeToDesc($photo['created']);
            $template->user = $photo['user'];
            $template->type = TimeLineDao::TIME_LINE_TYPE_GROUP_USER_POST_PHOTO;
            return (array)$template;
        }
    }
}