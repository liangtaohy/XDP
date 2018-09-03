<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/9/3
 * Time: 上午11:42
 */

namespace Xdp\Mail;
use Xdp\Contract\Mail\Mailer as MailerContract;

class Mailer implements MailerContract
{
    /**
     * 常用邮件模型
     *
     * @var array
     */
    protected $templates;

    /**
     * to recipients array
     *
     * @var array
     */
    protected $to;

    /**
     * from user
     *
     * @var
     */
    protected $from;

    /**
     * cc recipients array
     *
     * @var array
     */
    protected $cc;

    /**
     * bcc recipients array
     *
     * @var array
     */
    protected $bcc;

    /**
     * message queue
     *
     * @var mixed
     */
    protected $queue;

    protected $content;

    /**
     * 设置mail::To
     *
     * @param $users
     * @return mixed
     */
    public function to($users)
    {
        return $this;
    }

    /**
     * 设置mail::bcc
     *
     * @param $users
     * @return mixed
     */
    public function bcc($users)
    {
        return $this;
    }

    /**
     * 发送raw data
     *
     * @param $text
     * @param null $callback
     * @return mixed
     */
    public function raw($text, $callback = null)
    {
        return $this;
    }

    public function html($html, array $data = [], $callback = null)
    {
        return $this;
    }

    /**
     * 发送html邮件
     *
     * @param $view html模板
     * @param array $data 数据
     * @param null $callback
     * @return mixed
     */
    public function send($view, array $data = [], $callback = null)
    {
    }

    /**
     * 获取发送失败的接收者recipients
     *
     * @return mixed
     */
    public function failures()
    {

    }
}