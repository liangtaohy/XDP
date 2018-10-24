<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/23
 * Time: 下午7:14
 */
require_once __DIR__ . "/../../../vendor/autoload.php";

use PHPUnit\Framework\TestCase;

class TestWxPayRefund extends TestCase
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

        $refund = new \Xdp\Pay\WxPay\WxRefund($this->config);

        $refund->setTotalFee(100)
            ->setOutTradeNo('20181023180020188890')
            ->setRefundFee(100)
            ->setRefundDesc('测试退款');

        try {
            $res = $refund->refund();
        } catch (\Xdp\Pay\WxPay\Lib\WxPayException $e) {
            $this->assertEquals("退款申请接口中，缺少必填参数out_refund_no！", $e->getMessage());
        }

        echo $res . PHP_EOL;
        $this->assertTrue(true);
    }
}