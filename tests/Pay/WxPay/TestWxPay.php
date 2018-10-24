<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/23
 * Time: 下午5:38
 */
require_once __DIR__ . "/../../../vendor/autoload.php";

use PHPUnit\Framework\TestCase;

class TestWxPay extends TestCase
{
    public $config = [
        'appid' => 'wx460fc1efdfa5edfc',
        'app_secret' => '337aa836eaa5f344e8ff84c9e35685b8',
        'key' => '337aa836eaa5f344e8ff84c9e35685b8',
        'mch_id' => '1515513291',
        'notify_url' => 'https://x.xmanlegal.com',
        'sign_type' => 'MD5',
        'report_level' => 1
    ];

    public function testBasic()
    {
        $openid = "osswJ4w0D_TB7VTqtqfVMTKT6Ce4";
        $pay = new \Xdp\Pay\WxPay\WxPay($this->config, $openid);
        $pay->setBody("商品测试：测试法律咨询")
            ->setTradeType(\Xdp\Pay\WxPay\WxCommon::TRADE_TYPE_JSAPI)
            ->setOutTradeNo("20181023180020188890")
            ->setOpenId($openid)
            ->setTotalFee(100);
        $res = $pay->jsApi();
        echo $res . PHP_EOL;
        try {
            $res = json_decode($res, true);
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
        $this->assertEquals($this->config['appid'], $res['appId']);
    }
}