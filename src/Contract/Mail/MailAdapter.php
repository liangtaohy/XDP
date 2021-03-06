<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/31
 * Time: 下午5:57
 */

namespace Xdp\Contract\Mail;
use Exception;
/**
 * Interface MailAdapter
 * @package Xdp\Contract\Mail
 */
interface MailAdapter
{

    /**
     * 接收方
     * @param $addresses
     * @param null $name
     */
    public function to($addresses, $name = null);

    /**
     * 添加暗抄送
     * @param $addresses
     * @param null $name
     * @return MailAdapter
     */
    public function bcc($addresses, $name = null);


    /**
     * 设置email文本
     * @param $text
     * @return MailAdapter
     */
    public function text(string $text);


    /**
     * 设置发送方
     * @param string $mail
     * @param null $name
     * @return MailAdapter
     */
    public function from(string $mail, $name = null);

    /**
     * 设置email文本
     * @param $html
     * @return MailAdapter
     */
    public function html(string $html);


    /**
     * 添加明抄送
     * @param $addresses
     * @param null $name
     * @return MailAdapter
     */
    public function cc($addresses, $name = null);

    /**
     * 发送raw data
     *
     * @param $text
     * @param null $callback
     * @return MailAdapter
     */
    public function raw($text, $callback = null);


    /**
     * 设置主题
     * @param string $subject
     * @return MailAdapter
     */
    public function subject(string $subject);


    /**
     * 添加附件
     * @param $path
     * @param string $name
     * @param string $encoding
     * @param string $type
     * @param string $disposition
     * @return MailAdapter
     */
    public function attachment($path, $name = '', $encoding = 'base64', $type = '', $disposition = 'attachment');
    /**
     * 发送html邮件
     *
     * @param null $callback
     * @return mixed
     */
    public function send($callback = null);

    /**
     * 获取发送失败的接收者recipients
     * @param  Exception $exception
     * @return mixed
     */
    public function failures(Exception $exception);
}