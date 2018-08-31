<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/31
 * Time: 下午6:07
 */

namespace Xdp\Contract\Mail;

interface Factory
{
    /**
     * 根据app的mail配置，选择mail
     *
     * @note env("MAIL_DRIVER") := PHPMailer | RichSMTP | Swift_Mailer
     *      默认配置为swift mailer
     * @note PHPMailer、RichSMTP主要为兼容旧的mail系统
     *
     * @return \Xdp\Contract\Mail\Mailer
     */
    public function mailer();
}