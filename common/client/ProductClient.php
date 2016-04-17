<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 2:55 PM
 */

namespace Sucel\Common\Client;

class ProductClient extends CClient {

    public function info($pid) {
        return $this->request('product.info', array(
            'pid' => $pid
        ));
    }
}