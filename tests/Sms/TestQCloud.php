<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/25 13341007105@163.com
 * Time: 3:31 PM
 */

namespace Xdp\Test\Sms;

require_once __DIR__.'/../../vendor/autoload.php';

use Xdp\Test\XdpTestCase;
use Qcloud\Sms\SmsSingleSender;
use Qcloud\Sms\SmsMultiSender;

class TestQCloud extends XdpTestCase
{
    public function testSend()
    {
        $appid = 'xxxx'; // 1400开头

        $appkey = "xxxx";

        $phoneNumbers = ["13341007105", "12345678902", "12345678903"];

        try {
            $ssender = new SmsSingleSender($appid, $appkey);
            $result = $ssender->send(0, "86", $phoneNumbers[0], "验证码:1234。请妥善保管，打死不能告诉别人！", "", "");
            $rsp = json_decode($result);
            echo $result;
        } catch(\Exception $e) {
            echo var_dump($e);
        }
    }

    public function testSends()
    {
        $appid = 'xxx'; // 1400开头

        $appkey = "xxx";

        $phoneNumbers = ["17600777233"];
        try {
            $msender = new SmsMultiSender($appid, $appkey);
            $result = $msender->send(0, "86", $phoneNumbers,
                "验证码:1234。请妥善保管，打死不能告诉别人！", "", "");
            $rsp = json_decode($result);
            echo $result;
        } catch(\Exception $e) {
            echo var_dump($e);
        }
    }

}