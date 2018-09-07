<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/3 13341007105@163.com
 * Time: 下午1:27
 */

namespace Xdp\Mail;

use Xdp\Contract\Mail\Factory;
use Xdp\Contract\Mail\MailAdapter;
use Xdp\Mail\Exception\MailException;
use XdpLog\MeLog;
use Xdp\Contract\Mail\MailAdapter as BaseMailAdapter;

/**
 * Class MailFactory
 * @package Xdp\Mail
 */
class MailFactory implements Factory
{
    /**
     * 驱动器
     * @var null
     */
    private $driver = null;

    /**
     * @var array
     */
    protected $connections = [];

    /**
     * 获取 connection
     * @param $name
     * @return \Xdp\Contract\Mail\MailAdapter
     * @throws MailException
     * @throws \Xdp\Container\Exception\ContainerException
     */
    public function connection($name):BaseMailAdapter
    {
        if (isset($this->connections[$name]) && !empty($this->connections[$name])) {
            return $this->connections[$name];
        }

        $this->setDriver();
        $this->connections[$name] = $this->mailer($name);
        return $this->connections[$name];
    }

    /**
     * 设置driver
     * @param null $driver
     * @return mixed|null|string|\Xdp\Config\Config
     * @throws MailException
     */
    public function setDriver($driver = null)
    {
        if (is_null($driver)) {
            if (!$this->driver) {
                if (env('APP_MAIL_DRIVER')) {
                    $this->driver = config('mail.drivers.'.env('APP_MAIL_DRIVER'));
                } else {
                    $this->driver = 'Xdp\Mail\Adapter\SwiftMailAdapter';
                }
            }
        } else {
            if (!class_exists($driver)) {
                throw new MailException("driver is not class {$driver}");
            }
            $this->driver = $driver;
        }

        return $this;
    }
    /**
     * 获取邮件发送实例
     * @return MailAdapter
     * @throws \Xdp\Container\Exception\ContainerException
     */
    protected function mailer($name):MailAdapter
    {
        $config = config('mail.accounts.'.$name);
        MeLog::debug('driver :' . $this->driver . ' config : '.json_encode($config, JSON_UNESCAPED_UNICODE));

        return app()->resolve($this->driver, ['config' => $config]);
    }
}
