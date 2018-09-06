<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/4 13341007105@163.com
 * Time: 下午3:32
 */

namespace Xdp\Mail\Strategy;

use Xdp\Contract\Mail\MailConfigStrategy;

/**
 * Class RandMailConfigStrategy
 * @package Xdp\Mail\Strategy
 */
class RandMailConfigStrategy implements MailConfigStrategy
{
    /**
     * 选取配置
     * @return array
     */
    public function getConfig(): array
    {
        $config_keys = array_keys(config('mail.accounts'));
        return [$config_keys[rand(0, count($config_keys) - 1)]];
    }
}
