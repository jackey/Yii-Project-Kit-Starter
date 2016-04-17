<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 8/12/15
 * Time: 3:43 PM
 */

namespace Sucel\Common\Client;

class OrderClient extends CClient {

    public function create($product, $count, $date, $uid, $phone) {
        return $this->request('order.create', array(
            'product' => $product,
            'count' => $count,
            'phone' => $phone,
            'uid' => $uid,
            'psid' => $date
        ));
    }
}