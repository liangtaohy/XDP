<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/6 13341007105@163.com
 * Time: 下午1:04
 */

namespace Xdp\Mail;


use Xdp\Mail\Exception\MailException;
use Xdp\Utils\Traits\Singleton;
use Xdp\Container\Exception\ContainerException;
use XdpLog\MeLog;

/**
 * Class Mailer
 * @package Xdp\Mail
 */
class Mailer
{
    use Singleton;
    /**
     * text格式邮件
     */
    const MAIL_TYPE_TEXT = 'text';
    /**
     * html格式邮件
     */
    const MAIL_TYPE_HTML = 'html';
    /**
     * @var MailFactory
     */
    protected $factory = null;

    /**
     * @var array|mixed|null
     */
    protected $config = null;

    /**
     * @var \Xdp\Contract\Mail\MailConfigStrategy
     */
    protected $strategy = null;


    /**
     * Mailer constructor.
     * @throws MailException
     */
    private function __construct()
    {
        if (!env('APP_MAIL_TPL_PATH')) {
            throw new MailException('env APP_MAIL_TPL_PATH not exists');
        }
        @mkdir(env('APP_MAIL_TPL_PATH'), 0777, true);

        if (!env('APP_MAIL_DRIVER')) {
            throw new MailException('env APP_MAIL_DRIVER not exists');
        }
        if (!env('APP_MAIL_TPL_PATH')) {
            throw new MailException('env APP_MAIL_TPL_PATH not exists');
        }
        if (!env('APP_MAIL_STRATEGY')) {
            $strategy = 'Xdp\Mail\Strategy\PollMailConfigStrategy';
        } else {
            $strategy =  'Xdp\Mail\Strategy\\' . env('APP_MAIL_STRATEGY') . 'MailConfigStrategy';
        }
        $this->strategy =  new $strategy;

        $factory = new MailFactory();
        $this->factory = $factory;
        $this->config = $this->strategy->getConfig();
    }


    /**
     * 发送模版邮件
     * @param $to
     * @param string $tpl
     * @param string $subject
     * @param string $bcc
     * @param string $cc
     * @param null $attachment
     * @param array $params
     * @return bool
     * @throws MailException
     * @throws ContainerException
     */
    public static function sendTplMsg($to, string $tpl, string $subject, $bcc = '', $cc = '', $attachment = null, array $params = [])
    {
        $tpl = env('APP_MAIL_TPL_PATH') . DIRECTORY_SEPARATOR . $tpl.'.html';
        if (!file_exists($tpl)) {
            throw new MailException('tpl file not exists path ' . $tpl);
        }
        $data = file_get_contents($tpl);
        $content = self::parseTpl($params, $data);
        return self::getInstance()->send($to, $content, $subject, $bcc, $cc, $attachment, self::MAIL_TYPE_HTML);
    }

   

    /**
     * 解析模版
     * @param $tpl_params
     * @param $content
     * @return mixed
     */
    private static function parseTpl($tpl_params, $content)
    {
        foreach ($tpl_params as $key => $param) {
            $content = str_replace("{" . $key . "}", $param, $content);
        }

        return $content;
    }


    /**
     * 发送html邮件
     * @param $to
     * @param $html
     * @param string|null $subject
     * @param $bcc
     * @param $cc
     * @param null $attachment
     * @return bool
     * @throws MailException
     * @throws ContainerException
     */
    public static function sendHtml($to, $html, string $subject = null, $bcc, $cc, $attachment = null)
    {
        return self::getInstance()->send($to, $html, $subject, $bcc, $cc, $attachment, self::MAIL_TYPE_HTML);
    }

    /**
     * 发送普通文本邮件
     * @param $to
     * @param $message
     * @param $subject
     * @param $bcc
     * @param $cc
     * @param $attachment
     * @return bool
     * @throws MailException
     * @throws ContainerException
     */
    public static function sendMsg($to, $message, $subject, $bcc, $cc, $attachment)
    {
        return self::getInstance()->send($to, $message, $subject, $bcc, $cc, $attachment, self::MAIL_TYPE_TEXT);
    }


    /**
     * 发送多个html邮件
     * @param $msgs
     * @return bool
     */
    public static function row($msgs)
    {
        try{
            foreach ($msgs as $msg) {
                self::getInstance()->send(
                    $msg['to'],
                    $msg['body'] ?? null,
                    $msg['subject'] ?? null,
                    $msg['bcc']?? null,
                    $msg['cc']??null,
                    $msg['attachment']??null,
                    self::MAIL_TYPE_HTML
                );
            }
        }catch (MailException $mailException) {
            MeLog::warning($mailException->getMessage());
            return false;
        }catch (ContainerException $containerException) {
            MeLog::warning($containerException->getMessage());
            return false;
        }
        return true;
    }


    /**
     * 发送邮件
     * @param $to
     * @param string|null $message
     * @param string|null $subject
     * @param $bcc
     * @param $cc
     * @param null $attachment
     * @param string $type
     * @return bool
     * @throws MailException
     * @throws \Xdp\Container\Exception\ContainerException
     */
    public function send($to, string $message =null, string $subject = null, $bcc, $cc, $attachment = null, string $type)
    {

        foreach ($this->config as $from) {
            $mailer = $this->factory->connection($from);

            if (is_array($to)) {
                $mailer->to($to['address'], $to['name']);
            } else {
                $mailer->to($to);
            }
            $mailer->subject($subject);

            if ($type === self::MAIL_TYPE_HTML) {
                $mailer->html($message);
            } else {
                $mailer->text($message);
            }
            if (!empty($bcc)) {
                if (is_array($bcc)) {
                    $mailer->bcc($bcc['address'], $bcc['name']);
                } else {
                    $mailer->bcc($bcc);
                }
            }
            if (!empty($cc)) {
                if (is_array($cc)) {
                    $mailer->cc($cc['address'],$cc['name']);
                } else {
                    $mailer->cc($cc);
                }
            }

            if (!empty($attachment)) {
                if (is_array($attachment)) {
                    $mailer->attachment($attachment['path'], $attachment['name']);
                } else {
                    $mailer->attachment($attachment);
                }
            }
            if ($mailer->send()) {
                return true;
            }
        }
        return false;
    }

}