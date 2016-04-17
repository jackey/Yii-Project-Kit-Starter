<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 10:59 AM
 */

use Sucel\Service\Dao\ProductBaseDao;
use Sucel\Service\Dao\ProductSetDao;

class ProductCommand extends CConsoleCommand {

    public function actionDump() {
        print "生成产品 \n";
        $products = array(
            array(
                'name' => '曼荼罗展 MANDALA ATR SHOW by WONJOY',
                'desc' => "曼荼罗 -  神圣之净土，极致之净界，感受人生升华的玄妙 ；\n
    -	我们因缘相聚，相识相谈，而又重回彼此的世界。 \n
    \n
    每一场邂逅，都是久别重逢；\n
    每一个故事，都是倾国倾城；\n
    每一个印记，都是刻骨铭心… \n",
                'valid_date' => array(
                    '2015/12/18', '2015/12/19' , '2015/12/20'
                ),
                'address' => '上海市黄陂南路751号卓维700园区1号楼',
                'price' => '3000', // 单位: 分
                'stock' => '1500'
            )
        );

        foreach ($products as $product) {
            $query = new \CDbCriteria();
            $query->addCondition('Fname=:name');
            $query->params[':name'] = $product['name'];
            $productDao = ProductBaseDao::model()->find($query);
            if (!$productDao) {
                $productDao = new ProductBaseDao();
            }
            $productDao->Fname = getParam($product, 'name');
            $productDao->Fdesc = getParam($product, 'desc');
            $productDao->Faddress = getParam($product, 'address');
            $productDao->Fstock = getParam($product, 'stock');
            $productDao->Fstatus = ProductBaseDao::STATUS_ONLINE;
            $productDao->Fcreated = NOW;

            $productDao->save();
            $productID = $productDao->getPrimaryKey();

            // 生成可选日期
            $validDates = getParam($product, 'valid_date');
            foreach ($validDates as $date) {
                $query = new \CDbCriteria();
                $query->addCondition('Fname=:name')
                    ->addCondition('Fdate=:date')
                    ->addCondition('Fproduct=:product');
                $query->params[':name'] = $productDao->Fname;
                $query->params[':date'] = $date;
                $query->params[':product'] = $productID;

                $productSetDao = ProductSetDao::model()->find($query);

                if (!$productSetDao) {
                    $productSetDao = new ProductSetDao();
                }
                $productSetDao->Fname = $productDao->Fname;
                $productSetDao->Fproduct = $productID;
                $productSetDao->Fdate = $date;
                $productSetDao->Fcreated = NOW;
                $productSetDao->Fprice = getParam($product, 'price');
                $productSetDao->save();
            }

            print "添加产品成功. 产品ID: {$productID}\n";
        }
    }
}