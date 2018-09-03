<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/3 13341007105@163.com
 * Time: 下午1:27
 */

namespace Xdp\Mail;


use Xdp\Contract\Mail\Factory;

class MailFactory implements Factory
{
    public function mailer()
    {
        $config = config('mail');
    }
}