<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/4 13341007105@163.com
 * Time: 下午3:33
 */

namespace Xdp\Mail\Strategy;

use \Xdp\Contract\Mail\MailConfigStrategy;
use Xdp\Mail\Exception\MailException;

class IndexMailConfigStrategy implements MailConfigStrategy
{

    /**
     * 获取配置
     * @return array
     * @throws MailException
     */
    public function getConfig():array
    {
        if (!env('APP_MAIL_CONFIG_KEY')) {
            throw new MailException('env APP_MAIL_CONFIG_KEY not exists');
        }

        return [env('APP_MAIL_CONFIG_KEY')];
    }
}