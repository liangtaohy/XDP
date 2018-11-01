<?php

namespace Xdp\Sms\Adapter;

/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/25 13341007105@163.com
 * Time: 1:34 PM
 */

use Xdp\Contract\Sms\SmsAdapter;
use Xdp\Test\Sms\SmsTplConfig;
use Xdp\Utils\Traits\Singleton;
use Qcloud\Sms\SmsSingleSender;
use Xdp\Utils\Traits\Middleware;
use XdpLog\MeLog;

/**
 * Class QCloudAdapter
 * @package Xdp\Sms\Adapter
 */
class QCloudAdapter implements SmsAdapter
{
    use Singleton;
    use Middleware;

    /**
     * @var array
     */
    public static $smsMiddleware = [
        \Xdp\Sms\Middleware\SmsBlackMiddleware::class,
        \Xdp\Sms\Middleware\SmsLimitMiddleware::class
    ];

    /**
     * @var string
     */
    public $mobile = '';
    /**
     * @var array
     */
    public $config = [];

    /**
     * QCloudAdapter constructor.
     * @param $config
     */
    private function __construct($config)
    {
        $this->config = $config;
        $this->setMiddleWares(self::$smsMiddleware);
        return $this;
    }

    /**
     * @param $mobile
     * @param $code
     * @return bool
     */
    public function sendVCode($mobile, $code)
    {
        try {
            $ssender = new SmsSingleSender($this->config['app_key'], $this->config['app_secret']);
            $result = $ssender->sendWithParam(
                "86",
                $mobile,
                $this->config['vcode_tpl_code'],
                [intval($code)],
                $this->config['sign_name'],
                "",
                ""
            );
            $rsp = json_decode($result, true);
            MeLog::debug($result);

            if ($rsp['errmsg'] != 'OK') {
                return $this->failures($rsp['errmsg'], $rsp['result']);
            }
            return true;
        } catch (\Exception $exception) {
            return $this->failures($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @param $mobile
     * @param $msg_id
     * @param $data
     * @return bool
     * @throws \Xdp\Sms\Exception\SmsException
     */
    public function sendTplSms($mobile, $msg_id, $data)
    {
        $msg_id = SmsTplConfig::isValidMsgId($msg_id, 'qcloud');
        $data = array_values($data);
        try {
            $ssender = new SmsSingleSender($this->config['app_key'], $this->config['app_secret']);

            $result = $ssender->sendWithParam(
                "86",
                $mobile,
                $msg_id,
                $data,
                $this->config['sign_name'],
                "",
                ""
            );

            $rsp = json_decode($result, true);
            MeLog::debug($result);

            if ($rsp['errmsg'] != 'OK') {
                return $this->failures($rsp['errmsg'], $rsp['result']);
            }
            return true;
        } catch (\Exception $exception) {
            return $this->failures($exception->getMessage(), $exception->getCode());
        }
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

    /**
     * @param $mobile
     * @param $code
     * @return mixed|void
     */
    public function sendVoice($mobile, $code)
    {
        // TODO: Implement sendVoice() method.
    }
}
