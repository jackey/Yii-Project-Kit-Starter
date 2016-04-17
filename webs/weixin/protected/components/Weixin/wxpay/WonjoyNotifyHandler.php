<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 8:25 PM
 */

// 加载所必须的文件
require_once WXPAY_BASE_PATH.'/lib/WxPay.Data.php';
require_once WXPAY_BASE_PATH.'/lib/WxPay.Exception.php';
require_once WXPAY_BASE_PATH.'/lib/WxPay.Notify.php';
require_once WXPAY_BASE_PATH.'/lib/WxPay.Config.php';
require_once WXPAY_BASE_PATH.'/lib/WxPay.Api.php';

class WonjoyNotifyHandler extends WxPayNotify
{
    // 查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

    // 重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {

        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }

        \Sucel\Common\Includes\Logger::log()->info("回调数据: ". json_encode($data));

        $orderId = $data['out_trade_no'];
        \Sucel\Common\Includes\Logger::log()->info("回调数据: ". $orderId);

        // 生成票
        $ticketApi = new \Sucel\Common\Client\TicketClient;
        $ticketApi->generate($orderId);

        return true;
    }
}