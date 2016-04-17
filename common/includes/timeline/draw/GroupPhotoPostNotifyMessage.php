<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 19/11/15
 * Time: 11:51 AM
 */

namespace Sucel\Common\Includes\Timeline\Draw;

use Sucel\Common\Includes\Timeline\TimeLineTemplate;
use Sucel\Service\Dao\Group\GroupDao;
use Sucel\Service\Dao\Group\GroupPhotoDao;
use Sucel\Service\Dao\TimeLineDao;
use Sucel\Service\Dao\User\UserDao;
use Sucel\Service\Model\Group\GroupModel;
use Sucel\Service\Model\User\UserModel;

/**
 * 群组发布成员发布照片的 Timeline
 * @package Sucel\Common\Includes\Timeline\Draw
 */
class GroupPhotoPostNotifyMessage extends CDraw{

    public function action() {
        return TimeLineDao::ACTION_POST_PHOTO;
    }

    public function from(){
        return TimeLineDao::FROM_GROUP;
    }

    public function type() {
        return TimeLineDao::TYPE_MESSAGE;
    }

    /**
     * @param TimeLineDao $timeLineDao
     * @return string
     */
    public function draw($timeLineDao) {
        $uid = $timeLineDao->Fby_uid;
        $time = $timeLineDao->Fcreated;
        $toUid = $timeLineDao->Fto_uid;
        $pid = $timeLineDao->Ffrom_id;
        $groupPhotoDao = GroupPhotoDao::model()->findByPk($pid);
        if ($groupPhotoDao && $groupPhotoDao->Fstatus == GroupPhotoDao::STATUS_NORMAL) {
            $userDao = UserDao::model()->findByPk($uid);
            $groupDao = GroupDao::model()->findByPk($groupPhotoDao->Fgid);

            $template = new TimeLineTemplate();
            $template->message = '8小时在%s发布了一组照片';
            $template->user = array(
                'nickname' => $userDao->Fnickname,
                'uid' => $userDao->Fid,
                'avatar' => UserModel::avatarURLOrDefault($userDao->Favatar),
            );
            $template->time = timeToDesc($time);
            $template->type = TimeLineDao::TIME_LINE_TYPE_GROUP_USER_POST_PHOTO_MESSAGE;
            $template->group = array(
                'gid' => $groupDao->Fid,
                'name' => $groupDao->Ftitle,
                'avatar' => GroupModel::avatarURLOrDefault($groupDao->Favatar)
            );

            return (array)$template;
        }
    }
}