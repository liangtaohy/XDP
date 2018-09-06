<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/4 13341007105@163.com
 * Time: 下午3:07
 */

namespace Xdp\Contract\Mail;


/**
 * 邮箱配置策略
 * Interface MailConfigStrategy
 * @package Xdp\Contract\Mail
 */
interface MailConfigStrategy
{
    /**
     * 选取配置
     * @return mixed
     */
    function getConfig():array ;
}