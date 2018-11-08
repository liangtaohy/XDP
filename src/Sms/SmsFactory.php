<?php

namespace Xdp\Sms;

/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/26 13341007105@163.com
 * Time: 12:59 PM
 */


use Xdp\Sms\Adapter\AliDaYuAdapter;
use Xdp\Sms\Adapter\QCloudAdapter;
use Xdp\Sms\Adapter\SubMailAdapter;
use Xdp\Sms\Exception\SmsException;

/**
 * Class SmsFactory
 * @package Xdp\Sms
 */
class SmsFactory
{
    public static function create($driver = '')
    {
        $config = config('sms');

        if (empty($driver)) {
            $driver = $config['default_driver'];
        }

        switch ($driver) {
            case 'aliyun':
                $class = AliDaYuAdapter::getInstance($config['accounts'][$driver]);
                break;
            case 'qcloud':
                $class = QCloudAdapter::getInstance($config['accounts'][$driver]);
                break;
            case 'submail' :
                $class = SubMailAdapter::getInstance($config['voice_option']);
        }
        if (isset($class)) {
            return $class;
        }

        throw new SmsException('undefined  driver :' . $driver);
    }
}
