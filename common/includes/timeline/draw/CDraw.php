<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 19/11/15
 * Time: 11:52 AM
 */

namespace Sucel\Common\Includes\Timeline\Draw;

use Sucel\Service\Dao\TimeLineDao;

abstract class CDraw {

    protected static $_draw;

    public abstract function  action();

    public abstract function from();

    public abstract function type();

    /**
     * @param TimeLineDao $timeLineDao
     * @return mixed
     */
    public abstract function draw($timeLineDao);

    /**
     * 实例化所有的TimeLine绘制类
     * @return array(CDraw)
     */
    public static function allDraw() {
       if (!empty(self::$_draw)) return self::$_draw;

        $crtDir = __DIR__;
        $namespace = __NAMESPACE__;
        $iteartor = new \DirectoryIterator($crtDir);
        foreach ($iteartor as $drawFile) {
            if (!$drawFile->isDir() && !$drawFile->isDot() && $drawFile->getFilename() != 'CDraw.php')  {
                $class = str_replace('.php', '', sprintf("%s\\%s", $namespace, $drawFile->getFilename()));
                self::$_draw[$class] = new $class;
            }
        }
        return self::allDraw();
    }

}