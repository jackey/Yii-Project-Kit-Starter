<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 2:40 PM
 */

namespace Sucel\Service\Decorative;

use Sucel\Service\Dao\ProductBaseDao;
use Sucel\Service\Dao\ProductSetDao;
use Sucel\Service\Model\ProductModel;

class ProductDecoration {

    /**
     * @param ProductBaseDao $productDao
     * @param array(ProductSetDao) $productSetDaos
     * @return fixed
     */
    public static function productBasicInfo($productDao, $productSetDaos) {
        $product = array(
            'pid' => $productDao->getPrimaryKey(),
            'name' => $productDao->Fname,
            'desc' => $productDao->Fdesc,
            'address' => $productDao->Faddress,
            'stock' => ProductModel::countProductStock($productDao->getPrimaryKey()),
        );

        foreach ($productSetDaos as $productSetDao) {
            $product['set'][] = array(
                'psid' => $productSetDao->Fid,
                'name' => $productSetDao->Fname,
                'date' => $productSetDao->Fdate,
                'price' => $productSetDao->Fprice
            );
        }

        return $product;
    }
}