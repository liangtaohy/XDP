<?php

namespace Xdp\Sms\Adapter;

/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/26 13341007105@163.com
 * Time: 10:18 AM
 */

use Xdp\Contract\Sms\SmsAdapter;
use Xdp\Sms\Exception\SmsException;
use Xdp\Utils\Traits\Middleware;
use Xdp\Utils\Traits\Singleton;
use TopClient;
use AlibabaAliqinFcSmsNumSendRequest;
use Exception;
use XdpLog\MeLog;
use Xdp\Test\Sms\SmsTplConfig;

/**
 * Class AliDaYuAdapter
 * @package Xdp\Sms\Adapter
 */
class AliDaYuAdapter implements SmsAdapter
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
     * @var array|mixed|\Xdp\Config\Config
     */
    private $config = [];

    /**
     * AliDaYuAdapter constructor.
     * @param array $config
     */
    private function __construct($config = [])
    {
        if (empty($config)) {
            $config = config('sms.accounts.aliyun');
        }
        $this->config = $config;
        $this->setMiddleWares(self::$smsMiddleware);
        return $this;
    }


    /**
     * @param $mobile
     * @param $code
     * @return bool|mixed
     */
    public function sendVCode($mobile, $code)
    {
        try {
            $client = new TopClient($this->config['app_key'], $this->config['app_secret']);
            $req = new AlibabaAliqinFcSmsNumSendRequest;
            $req->setSmsType("normal");
            $req->setSmsFreeSignName($this->config['sign_name']);
            $req->setSmsParam(json_encode(["vcode"=>strval($code)]));
            $req->setRecNum(strval($mobile));
            $req->setSmsTemplateCode($this->config['vcode_tpl_code']);
            $ret = $client->execute($req);
            if ($ret->result->msg != 'OK') {
                return $this->failures(json_encode($ret));
            }
            return true;
        } catch (Exception $exception) {
            return $this->failures($exception->getMessage());
        }
    }


    /**
     * @param $mobile
     * @param $msg_id
     * @param $data
     * @return bool|mixed
     * @throws SmsException
     */
    public function sendTplSms($mobile, $msg_id, $data)
    {
        $msg_id = SmsTplConfig::isValidMsgId($msg_id, 'aliyun');
        try {
            $client = new TopClient($this->config['app_key'], $this->config['app_secret']);
            $req = new AlibabaAliqinFcSmsNumSendRequest;
            $req->setSmsType("normal");
            $req->setSmsFreeSignName($this->config['sign_name']);
            $req->setSmsParam(json_encode($data));
            $req->setRecNum(strval($mobile));
            $req->setSmsTemplateCode($msg_id);
            $ret = $client->execute($req);
            if ($ret->result->msg != 'OK') {
                return $this->failures(json_encode($ret));
            }
            return true;
        } catch (Exception $exception) {
            return $this->failures($exception->getMessage());
        }
    }

    /**
     * 获取失败的接收者以及原因
     * @param $error_msg
     * @param null $error_code
     * @return bool|mixed
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
