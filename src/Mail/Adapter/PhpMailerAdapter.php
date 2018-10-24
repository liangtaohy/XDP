<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/1 13341007105@163.com
 * Time: 下午5:35
 */

namespace Xdp\Mail\Adapter;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Xdp\Contract\Mail\MailAdapter as MailAdapterContract;
use XdpLog\MeLog;

/**
 * PHPMailer适配器
 * Class PhpMailerAdapter
 * @package Xdp\Mail
 */
class PhpMailerAdapter implements MailAdapterContract
{
    use MailAdapter;

    /**
     * PhpMailerAdapter constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->from($config['username'], $config['name']);
        if (is_null($this->mailer)) {
            $this->mailer = new PHPMailer();
        }
        return $this;
    }



    /**
     * 发送mail
     * @param null $callback
     * @return bool|mixed
     */
    public function send($callback = null)
    {
        try {
            $mail = $this->mailer;
            $mail->CharSet = $this->config['charset'];
            $mail->IsSMTP();
            $mail->SMTPDebug = isset($this->config['debug']) ? intval($this->config['debug']) : 0;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = env('APP_MAIL_SECURITY') ?? null;
            $mail->Host = $this->config['host'];
            $mail->Port = $this->config['port'];
            $mail->Username = $this->config['username'];
            $mail->Password = $this->config['password'];
            $mail->Subject = $this->subject;
            $mail->setFrom($this->from['addresses'], $this->from['name']);

            //添加明抄送
            if (!empty($this->cc)) {
                foreach ($this->cc as $email => $to) {
                    $mail->addCC($email, $to);
                }
            }

            //添加暗抄送
            if (!empty($this->bcc)) {
                foreach ($this->bcc as $email => $to) {
                    $mail->addBCC($email, $to);
                }
            }
            //添加发送方
            foreach ($this->to as $email => $to) {
                $mail->addAddress($email, $to);
            }


            $body = empty($this->html) ? $this->text : $this->html;
            $mail->msgHTML($body);

            //发送消息
            if (!empty($this->attachment)) {
                $mail->addAttachment($this->attachment['path'], $this->attachment['name']);
            }
            if (!$mail->send()) {
                throw new Exception($mail->ErrorInfo);
            }
            if (!is_null($callback)) {
                file_get_contents($callback);
            }
            $this->clear();
            return true;
        } catch (Exception $exception) {
            $this->failures($exception);
        }
        return false;
    }

    /**
     * 发送邮件错误记录
     * @param Exception $exception
     * @return mixed|void
     */
    public function failures(Exception $exception)
    {
        MeLog::warning(sprintf(
            self::$logStr,
            json_encode($this->to, JSON_UNESCAPED_UNICODE),
            json_encode($this->from, JSON_UNESCAPED_UNICODE),
            $exception->getMessage()
        ));
    }
}
