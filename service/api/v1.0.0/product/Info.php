<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 2:22 PM
 */

namespace Sucel\Service\Api\Product;

use Sucel\Common\Description\ErrorDesc;
use Sucel\Service\Api\BaseApi;
use Sucel\Service\Decorative\ProductDecoration;
use Sucel\Service\Model\ProductModel;

class Info extends BaseApi {

    public function rules() {
        return array(
            'pid' => array(
                'validator' => array(
                    'Required' => array(
                        'code' => ErrorDesc::PRODUCT_NOT_EXIST,
                        'message' => ErrorDesc::mean(ErrorDesc::PRODUCT_NOT_EXIST)
                    )
                )
            )
        );
    }

    public function run() {
        list($productDao, $productSetDaos) = ProductModel::loadProductInfo($this->pid);

        return ProductDecoration::productBasicInfo($productDao, $productSetDaos);
    }
}