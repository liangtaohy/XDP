<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/1 13341007105@163.com
 * Time: 下午5:35
 */

namespace Xdp\Mail\Adapter;


use Xdp\Contract\Mail\Mailer;
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;
use Exception;
use Swift_Attachment;
use XdpLog\MeLog;

/**
 * SwiftMail适配器
 * Class SwiftMailAdapter
 * @package Xdp\Mail
 */
class SwiftMailAdapter implements Mailer
{

    use MailAdapter;

    /**
     * 发送mail
     * @param null $callback
     * @return bool|mixed
     */
    public function send($callback = null)
    {
        try {
            //装填配置项
            $transport = (new Swift_SmtpTransport($this->config['host'], $this->config['port']))
                ->setUsername($this->config['username'])
                ->setPassword($this->config['password']);
            //实例化mailer
            $mailer = new Swift_Mailer($transport);
            //装填消息体
            $message = new Swift_Message($this->subject);

            $message->setFrom($this->from['addresses'],$this->from['name']);

            $message->setTo($this->to);

            if (!empty($this->cc)) {
                $message->setCc($this->cc);
            }

            if (!empty($this->bcc)) {
                $message->setBcc($this->bcc);
            }

            if (!is_null($this->html)) {
                $message->setBody($this->html,'text/html');
            }
            if (!is_null($this->text)) {
                $message->setBody($this->text);
            }

            if (!empty($this->attachment)) {
                $attachment = Swift_Attachment::fromPath($this->attachment['path'])
                    ->setFilename($this->attachment['name']);
                // 添加附件
                $message->attach($attachment);
            }
            //发送消息
            if ($mailer->send($message)) {
                if (!is_null($callback)) {
                    file_get_contents($callback);
                }
                $this->clear();
                return true;
            }
        } catch (Exception $exception) {
            $this->failures($exception);
            return false;
        }

        return false;
    }

    /**
     * @param Exception $exception
     * @return mixed|void
     */
    public function failures(Exception $exception)
    {
        MeLog::warning($exception->getMessage());
    }
}