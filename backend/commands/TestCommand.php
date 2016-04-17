<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 23/11/15
 * Time: 12:04 PM
 */

class TestCommand extends CConsoleCommand {
    public function actionIMAddAccount () {

    }

    public function actionCreateTags() {
        $tags = array(
            '挑战', '纹身', '打球', '喝酒', '旅游', '户外', '摄影', '健身', '唱歌', '玩'
        );

        foreach ($tags as $tag) {
            $tagDao = new \Sucel\Service\Dao\Tags\TagsDao();
            $tagDao->Fname = $tag;
            $tagDao->Fcreated = 1;
            $tagDao->Fcreated_uid = NULL;
            $tagDao->save();
        }
    }

    public function actionCreateUsers() {
        $phones = array(
            array(
                '15821221754',
                '小明',
            ),
            array(
                '15821221753',
                '小红',
            ),
            array(
                '15821221755',
                '小张',
            ),
            array(
                '15821221756',
                '小李',
            ),
        );

        foreach ($phones as $user) {
            $userDao = new \Sucel\Service\Dao\User\UserDao();
            $userDao->Fnickname = $user[1];
            $userDao->Fpassword = encryptPassword('123456');
            $userDao->Fphone = $user[0];
            $userDao->save();
        }
    }

    public function actionCreateGroups() {
        $groups = array(
            array(
                '老上海',
                '老上海人聚集于此',
                1870
            ),
            array(
                '喝酒High歌',
                '潮人欢迎您',
                1870
            ),
            array(
                '老胡同的人',
                '老上海人聚集于此',
                1870
            ),
            array(
                '设计师的小聚',
                '都是设计师 不要为难自己人',
                1870
            ),
        );
        foreach ($groups as $group) {
            $groupDao = new \Sucel\Service\Dao\Group\GroupDao();
            $groupDao->Ftitle = $group[0];
            $groupDao->Fslogan = $group[1];
            $groupDao->Fuid = $group[2];
            $groupDao->Fcreated = NOW;
            $groupDao->Fstatus = \Sucel\Service\Dao\Group\GroupDao::STATUS_NORMAL;
            $groupDao->save();
        }
    }
}