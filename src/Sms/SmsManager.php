<?php

namespace Xdp\Sms;

/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/26 13341007105@163.com
 * Time: 1:02 PM
 */

use Xdp\Contract\Sms\Sms;
use Xdp\Sms\Exception\SmsException;
use Xdp\Utils\Traits\Singleton;

/**
 * Class SmsManager
 * @package Xdp\Sms
 */
class SmsManager implements Sms
{
    use Singleton;

    /**
     * @var \Xdp\Contract\Sms\SmsAdapter
     */
    private static $smsClient;


    /**
     * @param string $driver
     * @return \Xdp\Contract\Sms\SmsAdapter
     * @throws Exception\SmsException
     */
    private static function getSmsClient($driver = '')
    {
        self::$smsClient = SmsFactory::create($driver);
        return self::$smsClient;
    }

    /**
     * @param $mobile
     * @param $tpl_id
     * @param $params
     * @return bool|mixed
     * @throws SmsException
     */
    public static function sendTplSms($mobile, $tpl_id, $params)
    {
        self::getSmsClient();
        self::$smsClient->useMiddleWare($mobile);
        try{
            if (self::$smsClient->sendTplSms($mobile, $tpl_id, $params)){
                return true;
            }
            $sms = config('sms');
            $default_driver = $sms['default_driver'];
            $accounts = $sms['accounts'];
            foreach ($accounts as $driver => $option) {
                if ($default_driver== $driver) {
                    continue;
                }
                self::getSmsClient($driver);
                if (self::$smsClient->sendTplSms($mobile, $tpl_id, $params)){
                    return true;
                }
            }
            return false;
        }catch (\Exception $exception) {
            throw new SmsException($exception->getMessage());
        }
    }


    /**
     * @param $mobile
     * @param $code
     * @return bool|mixed
     * @throws SmsException
     */
    public static function sendVCode($mobile, $code)
    {
        self::getSmsClient();
        self::$smsClient->useMiddleWare($mobile);
        try{
            if (self::$smsClient->sendVCode($mobile, $code)){
                return true;
            }
            $sms = config('sms');
            $default_driver = $sms['default_driver'];
            $accounts = $sms['accounts'];
            foreach ($accounts as $driver => $option) {
                if ($default_driver== $driver) {
                    continue;
                }
                self::getSmsClient($driver);
                if (self::$smsClient->sendVCode($mobile, $code)){
                    return true;
                }
            }
            return false;
        }catch (\Exception $exception) {
            throw new SmsException($exception->getMessage());
        }
    }

    public static function sendSms($mobile, $data)
    {
        // TODO: Implement sendSms() method.
    }

    /**
     * @param $mobile
     * @param $code
     * @return mixed
     * @throws SmsException
     */
    public static function sendVoice($mobile, $code)
    {
        self::getSmsClient('submail');

        self::$smsClient->useMiddleWare($mobile);

        return self::$smsClient->sendVoice($mobile, $code);
    }
}