<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 7/12/15
 * Time: 6:51 PM
 */

namespace Sucel\Service\Model;

use Sucel\Common\Description\ErrorDesc;
use Sucel\Service\Dao\SMSCodeDao;

class SMSModel {

    const VALID_CODE_LENGTH = 6;
    const EXPIRED_SECOND = 1800; // 半小时内过期
    const EXPIRED_SECOND_DSC = '半';

    public static function sendValidCodeWhenBuyTicket($name,$phone) {
        $code = \SMS::randomCode(self::VALID_CODE_LENGTH);

        $data = array(
            'Fcode' => $code,
            'Fcreated' => NOW,
            'Fstatus' => SMSCodeDao::STATUS_UNUSED,
            'Fexpired' => NOW + self::EXPIRED_SECOND,
            'Ftype' => SMSCodeDao::TYPE_VALID,
            'Fphone' => $phone
        );

        $smsCodeDao = new SMSCodeDao();
        $smsCodeDao->setAttributes($data, false);
        $ret = $smsCodeDao->save();

        if ($ret)
            return  \SMS::instance()->sendValidWithExpireCode($phone, $code, $name, self::EXPIRED_SECOND_DSC);
    }

    public static function validCodeWhenBuyTicket($phone, $code) {
        $query = new \CDbCriteria();
        $query->addCondition('Fphone=:phone')
            ->addCondition('Fcode=:code')
            ->addCondition('Fstatus=:status');
        $query->order = 'Fcreated DESC';
        $query->params[':status'] = SMSCodeDao::STATUS_UNUSED;
        $query->params[':code'] = $code;
        $query->params[':phone'] = $phone;

        $smsCodeDao = SMSCodeDao::model()->find($query);

        if (!$smsCodeDao || $smsCodeDao->Fexpired < NOW) {
            throw new \CHttpException(ErrorDesc::CODE_UNKNOWN_ERROR, ErrorDesc::mean(ErrorDesc::CODE_UNKNOWN_ERROR));
        }

        $smsCodeDao->Fstatus = SMSCodeDao::STATUS_USED;
        $smsCodeDao->save();

        return true;
    }
}