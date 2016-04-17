<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 9:24 PM
 */
namespace Sucel\Common\Client;

class TicketClient extends CClient {

    public function generate($orderID) {
        return $this->request('ticket.generate', array(
            'order' => $orderID
        ));
    }

    public function getMyTickets($uid, $offset = 0, $limit = 10) {
        return $this->request('ticket.mine', array(
            'uid' => $uid,
            'offset' => $offset,
            'limit' => $limit
        ));
    }

    public function getMyTicketsWithOpenID($openid, $offset = 0, $limit = 10) {
        return $this->request('ticket.mine', array(
            'openid' => $openid,
            'offset' => $offset,
            'limit' => $limit
        ));
    }
}