<?php

namespace Xdp\Sms\Adapter;

/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/31 13341007105@163.com
 * Time: 12:29 PM
 */

use Xdp\Contract\Sms\SmsAdapter;
use Xdp\Utils\Traits\Middleware;
use Xdp\Utils\Traits\Singleton;
use voiceverify;
use XdpLog\MeLog;

/**
 * Class SubMailAdapter
 * @package Xdp\Sms\Adapter
 */
class SubMailAdapter implements SmsAdapter
{
    use Middleware;
    use Singleton;

    /**
     * @var array|mixed|\Xdp\Config\Config
     */
    private $config = [];
    /**
     * @var array
     */
    private static $smsMiddleware = [
        \Xdp\Sms\Middleware\SmsBlackMiddleware::class,
        \Xdp\Sms\Middleware\SmsLimitMiddleware::class
    ];

    /**
     * SubMailAdapter constructor.
     * @param array $config
     */
    private function __construct($config = [])
    {
        if (empty($config)) {
            $config = config('sms.voice_option');
        }
        $this->config = $config;
        $this->setMiddleWares(self::$smsMiddleware);
        return $this;
    }


    /**
     * @param $mobile
     * @param $code
     * @return mixed|void
     */
    public function sendVCode($mobile, $code)
    {
        // TODO: Implement sendVCode() method.
    }

    /**
     * @param $mobile
     * @param $code
     * @return mixed
     */
    public function sendVoice($mobile, $code)
    {
        $submail=new voiceverify($this->config);
        $submail->SetTo($mobile);
        $submail->SetCode($code);
        $ret = $submail->verify();
        if ($ret['status'] == 'error') {
            return $this->failures($ret['msg']);
        }
        return true;
    }

    /**
     * @param $mobile
     * @param $msg_id
     * @param $data
     * @return mixed|void
     */
    public function sendTplSms($mobile, $msg_id, $data)
    {
        // TODO: Implement sendTplSms() method.
    }

    /**
     * @param $error_msg
     * @param null $error_code
     * @return bool
     */
    public function failures($error_msg, $error_code = null)
    {
        $error = [
            'code' => $error_code,
            'message' => $error_msg
        ];
        MeLog::warning(json_encode($error, JSON_UNESCAPED_UNICODE));
        return false;
    }
}
