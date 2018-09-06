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
use Xdp\Contract\Mail\MailAdapter as BaseMailAdapter;

/**
 * Interface Factory
 * @package Xdp\Contract\Mail
 */
interface Factory
{
    /**
     * 通过配置名称获取 $driver
     * @param $name
     * @return MailAdapter
     */
    public function connection($name):BaseMailAdapter;

    /**
     * @param null $driver
     * @return mixed
     */
    public function setDriver($driver = null);
}
