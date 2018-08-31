<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/31
 * Time: 下午6:04
 */

namespace Xdp\Contract\Mail;


interface MailQueue
{
    public function queue($view, $queue = null);

    public function later($delay, $view, $queue = null);
}