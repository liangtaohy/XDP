<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/31
 * Time: 下午6:22
 */

namespace Xdp\Contract\Sms;

/**
 * Interface Sms
 * @package Xdp\Contract\Sms
 */
interface Sms
{


    /**
     * 发送文本消息
     * @param $mobile
     * @param $data
     * @return mixed
     */
    public static function sendSms($mobile, $data);


    /**
     * 发送语音验证码
     * @param $mobile
     * @param $code
     * @return mixed
     */
    public static function sendVoice($mobile, $code);


    /**
     * 发送验证码
     * @param $mobile
     * @param $code
     * @return mixed
     */
    public static function sendVCode($mobile, $code);


    /**
     * 发送模版消息
     * @param $mobile
     * @param $msg_id
     * @param $params
     * @return mixed
     */
    public static function sendTplSms($mobile, $msg_id, $params);
}
