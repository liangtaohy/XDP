<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/26 13341007105@163.com
 * Time: 1:07 PM
 */

namespace Xdp\Test\Sms;

require_once __DIR__ . '/../../vendor/autoload.php';

use Xdp\Sms\SmsFactory;
use Xdp\Sms\SmsManager;
use Xdp\Test\XdpTestCase;

class TestSmsManager extends XdpTestCase
{

    public $mobile = 13341007105;

    public $vcode = 1234;

    public function testCase01()
    {
        $this->runApp();

        $ret = SmsManager::sendTplSms($this->mobile, SmsTplConfig::FILE_PRICE_FAIL, $params = [
            'message' => 'this is msg',
            'file_id' => 'this is file_id',
            'log_id' => 'this is log_id'
        ]);
        $this->assertEquals($ret, true);
        $ret = SmsManager::sendVCode($this->mobile, $this->vcode);
        $this->assertEquals($ret, true);
        $ret = SmsManager::sendVoice($this->mobile, $this->vcode);
        $this->assertEquals($ret, true);
    }
}