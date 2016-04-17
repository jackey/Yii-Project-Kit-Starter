<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 2:24 PM
 */
namespace Sucel\Service\Model;

use Sucel\Common\Description\ErrorDesc;
use Sucel\Service\Dao\ProductBaseDao;
use Sucel\Service\Dao\ProductSetDao;
use Sucel\Service\Dao\TicketDao;

class ProductModel {

    public static function loadProductInfo($pid) {
        $productDao = ProductBaseDao::model()->findByPk($pid);
        if (!$productDao) {
            throw new \CHttpException(ErrorDesc::PRODUCT_NOT_EXIST, ErrorDesc::mean(ErrorDesc::PRODUCT_NOT_EXIST));
        }

        $query = new \CDbCriteria();
        $query->addCondition('Fproduct=:product');
        $query->params[':product'] = $productDao->getPrimaryKey();

        $productSetDaos = ProductSetDao::model()->findAll($query);

        return array($productDao, $productSetDaos);
    }

    public static function countProductStock($pid) {
        $query = new \CDbCriteria();
        $query->addCondition('Fproduct=:product');
        $query->params[':product'] = $pid;

        $boughtTicketCount= TicketDao::model()->count($query);
        $productBaseDao = ProductBaseDao::model()->findByPk($pid);

        return $productBaseDao->Fstock - $boughtTicketCount;
    }
}