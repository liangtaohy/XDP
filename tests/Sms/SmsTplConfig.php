<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/31 13341007105@163.com
 * Time: 12:37 PM
 */

namespace Xdp\Test\Sms;
use Xdp\Sms\Exception\SmsException;


/**
 * Class SmsTplConfig
 * @package Xdp\Test\Sms
 */
class SmsTplConfig
{

    /**
     *
     */
    const SEND_VCODE_TPL = 'sms_001';

    /**
     *
     */
    const FILE_PRICE_FAIL = 'sms_002';

    /**
     * @var array
     */
    public static $verify_fields = [
        self::SEND_VCODE_TPL => [
            'vcode'
        ],
        self::FILE_PRICE_FAIL =>[
            'message',
            'file_id',
            'log_id'
        ]
    ];

    /**
     * @var array
     */
    public static $tpl = [
        self::SEND_VCODE_TPL => [
            'aliyun' => 'SMS_76550018',
            'qcloud' => 122447,
        ],
        self::FILE_PRICE_FAIL => [
            'aliyun' => 'SMS_147439350',
            'qcloud' => 217500,
        ],
    ];


    /**
     * @param $tpl_id
     * @param $params
     * @return bool
     * @throws SmsException
     */
    public static function verifySmsTplFields($tpl_id, $params)
    {
        if (!isset(self::$tpl[$tpl_id]) || !isset(self::$verify_fields[$tpl_id])) {
            return false;
        }

        if (empty(self::$verify_fields[$tpl_id])) {
            return true;
        }

        foreach (self::$verify_fields[$tpl_id] as $field) {
            if (!isset($params[$field])) {
                throw new SmsException("tpl need params :{$field}");
            }
        }

        return true;
    }


    /**
     * @param $msg_id
     * @param $driver
     * @return mixed
     * @throws SmsException
     */
    public static function isValidMsgId($msg_id, $driver)
    {

        if (isset(self::$tpl[$msg_id])) {
            return self::$tpl[$msg_id][$driver];
        }

        throw new SmsException("tpl undefind tpl:{$msg_id}");
    }
}