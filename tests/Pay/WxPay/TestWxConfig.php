<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/23
 * Time: 下午5:01
 */
require_once __DIR__ . "/../../../vendor/autoload.php";

use PHPUnit\Framework\TestCase;

class TestWxConfig extends TestCase
{
    public $config = [
        'appid' => 'wxc6b89ce6de231748',
        'app_secret' => '337aa836eaa5f344e8ff84c9e35685b8',
        'key' => 'testkey',
        'mch_id' => '商户号',
        'notify_url' => 'testnotifyurl',
        'sign_type' => 'MD5',
        'report_level' => 0
    ];

    /**
     * TestWxConfig constructor.
     */
    public function testWxConfigBasic()
    {
        $wxConfig = new \Xdp\Pay\WxPay\WxPayConfig($this->config);

        $this->assertEquals('testkey', $wxConfig->GetKey());

        $this->assertEquals('wxc6b89ce6de231748', $wxConfig->GetAppId());
        $this->assertEquals('337aa836eaa5f344e8ff84c9e35685b8', $wxConfig->GetAppSecret());
        $this->assertEquals('商户号', $wxConfig->GetMerchantId());
        $this->assertEquals('testnotifyurl', $wxConfig->GetNotifyUrl());
        $this->assertEquals('MD5', $wxConfig->GetSignType());
        $this->assertEquals('0', $wxConfig->GetReportLevenl());
    }
}