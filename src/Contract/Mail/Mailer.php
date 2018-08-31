<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/31
 * Time: 下午5:57
 */

namespace Xdp\Contract\Mail;

interface Mailer
{
    /**
     * 设置mail::To
     *
     * @param $users
     * @return mixed
     */
    public function to($users);

    /**
     * 设置mail::bcc
     *
     * @param $users
     * @return mixed
     */
    public function bcc($users);

    /**
     * 发送raw data
     *
     * @param $text
     * @param null $callback
     * @return mixed
     */
    public function raw($text, $callback = null);

    /**
     * 发送html邮件
     *
     * @param $view html模板
     * @param array $data 数据
     * @param null $callback
     * @return mixed
     */
    public function send($view, array $data = [], $callback = null);

    /**
     * 获取发送失败的接收者recipients
     *
     * @return mixed
     */
    public function failures();
}