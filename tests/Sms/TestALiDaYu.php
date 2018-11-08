<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/25 13341007105@163.com
 * Time: 4:54 PM
 */

namespace Xdp\Test\Sms;
require_once __DIR__ . '/../../vendor/autoload.php';


use Xdp\Sms\Adapter\AliDaYuAdapter;
use Xdp\Sms\Exception\SmsException;
use Xdp\Test\XdpTestCase;

class TestALiDaYu extends XdpTestCase
{
    public $mobile = "13341007105";

    public $v_code = 1234;

    public function testError()
    {
        $this->runApp();

        $params = [
            'message' => 'this is msg',
            'file_id' => 'this is file_id',
            'log_id' => 'this is log_id'
        ];
        $ret = AliDaYuAdapter::getInstance()->sendTplSms($this->mobile, 'error_msg_id', $params);
        unset($params['log_id']);
        $ret = AliDaYuAdapter::getInstance()->sendTplSms($this->mobile, SmsTplConfig::FILE_PRICE_FAIL, $params);
    }

    public function testSendVCode()
    {
        $this->runApp();
        $ret = AliDaYuAdapter::getInstance()->sendVCode($this->mobile, $this->v_code);
        $this->assertEquals($ret, true);
    }

    public function testSendTplSms()
    {
        $this->runApp();

        $params = [
            'message' => 'this is msg',
            'file_id' => 'this is file_id',
            'log_id' => 'this is log_id'
        ];
        $ret = AliDaYuAdapter::getInstance()->sendTplSms($this->mobile, SmsTplConfig::FILE_PRICE_FAIL, $params);
        $this->assertEquals($ret, true);
    }
}