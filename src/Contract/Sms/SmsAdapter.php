<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/31 13341007105@163.com
 * Time: 3:16 PM
 */

namespace Xdp\Contract\Sms;


/**
 * Interface SmsAdapter
 * @package Xdp\Contract\Sms
 */
interface SmsAdapter
{

    /**
     * 发送短信验证码
     * @param $mobile
     * @param $code
     * @return mixed
     */
    public function sendVCode($mobile, $code);

    /**
     * 发送语音验证码
     * @param $mobile
     * @param $code
     * @return mixed
     */
    public function sendVoice($mobile, $code);

    /**
     * 发送模版消息
     * @param $msg_id
     * @param $mobile
     * @param $data
     * @return mixed
     */
    public function sendTplSms($mobile, $msg_id, $data);

    /**
     * 错误信息处理
     * @param $error_msg
     * @param null $error_code
     * @return mixed
     */
    public function failures($error_msg, $error_code = null);

    /**
     * 添加多个中间件
     * @param array $middlewares
     * @return mixed
     */
    public function setMiddleWares(array $middlewares);

    /**
     * 添加一个中间件
     * @param $middleware
     * @return mixed
     */
    public function setMiddleWare($middleware);

    /**
     * 使用中间件
     * @param $mobile
     * @return mixed
     */
    public function useMiddleWare($mobile);
}