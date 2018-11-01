<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/4 13341007105@163.com
 * Time: 上午11:14
 */

namespace Xdp\Sms\Exception;

use Exception;
use XdpLog\MeLog;

class SmsException extends Exception
{
    public function __construct($message = "")
    {
        MeLog::warning($message);
        parent::__construct($message);
    }

}