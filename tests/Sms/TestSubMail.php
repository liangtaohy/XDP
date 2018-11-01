<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/31 13341007105@163.com
 * Time: 4:40 PM
 */

namespace Xdp\Test\Sms;

require_once __DIR__ . '/../../vendor/autoload.php';

use Xdp\Sms\Adapter\SubMailAdapter;
use Xdp\Test\XdpTestCase;

class TestSubMail extends XdpTestCase
{
    public function testSubMail()
    {
        $this->runApp();

        $mail = SubMailAdapter::getInstance()->sendVoice('13341007105', 1234);
        var_dump($mail);
        die;
    }
}