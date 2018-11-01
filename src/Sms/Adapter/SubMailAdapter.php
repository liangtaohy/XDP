<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/31 13341007105@163.com
 * Time: 12:29 PM
 */

namespace Xdp\Sms\Adapter;


use Xdp\Contract\Sms\SmsAdapter;
use Xdp\Utils\Traits\Middleware;
use Xdp\Utils\Traits\Singleton;
use voiceverify;

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
    function __construct($config = [])
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
        return $submail->verify();
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
     * @return mixed|void
     */
    public function failures($error_msg, $error_code = null)
    {
        // TODO: Implement failures() method.
    }
}