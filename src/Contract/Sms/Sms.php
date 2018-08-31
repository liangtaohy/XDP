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
     * 短信接收人
     *
     * @param $mobile
     * @return mixed
     */
    public function to($mobile);

    /**
     * 发送文本消息
     *
     * @param $msgid 消息id
     * @param $data 消息数据
     * @return mixed
     */
    public function sendSms($msgid, $data);

    /**
     * 发送语音数据
     *
     * @param $msgid
     * @param $data
     * @return mixed
     */
    public function sendVoice($msgid, $data);

    /**
     * 发送验证码
     *
     * @param $msgid
     * @param $data
     * @return mixed
     */
    public function sendVcode($msgid, $data);

    /**
     * 获取失败的接收者以及原因
     *
     * @return array
     */
    public function failures();

    /**
     * 检查是否为有效的msg id
     * @param $msgid
     * @return mixed
     */
    public function isValidMsgId($msgid);
}