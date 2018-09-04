<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/31
 * Time: 下午6:07
 */

namespace Xdp\Contract\Mail;

/**
 * Interface Factory
 * @package Xdp\Contract\Mail
 */
interface Factory
{
    /**
     * 根据app的mail配置，选择mail
     * @note env("MAIL_DRIVER") := PHPMailer  Swift_Mailer
     *      默认配置为swift mailer
     * @param  $driver
     * @param null $user
     * @return mixed
     */
    public function mailer($driver = 'SwiftMailer', $user = null);
}
