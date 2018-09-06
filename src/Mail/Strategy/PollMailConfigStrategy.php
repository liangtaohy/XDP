<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/4 13341007105@163.com
 * Time: 下午3:06
 */

namespace Xdp\Mail\Strategy;

use \Xdp\Contract\Mail\MailConfigStrategy;

/**
 * Class PullMailConfigStrategy
 * @package Xdp\Mail\Strategy
 */
class PollMailConfigStrategy implements MailConfigStrategy
{
    /**
     * 获取mail配置
     * @return array|mixed
     */
    public function getConfig():array
    {
        return array_keys(config('mail.accounts'));
    }
}