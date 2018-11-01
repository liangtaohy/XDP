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
    public function testCase01()
    {
        $this->runApp();

        $manager = SmsManager::getInstance();
        $manager::sendTplMsg('13341007105', 209184, [
            'test msg',
            'logssas',
        ]);
    }
}