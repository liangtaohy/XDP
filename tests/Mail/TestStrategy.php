<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/4 13341007105@163.com
 * Time: 下午6:44
 */

namespace Xdp\Test\Mail;

require_once __DIR__ . "/../../vendor/autoload.php";

use Xdp\Mail\MailFactory;
use Xdp\Mail\Strategy\IndexMailConfigStrategy;
use Xdp\Mail\Strategy\PollMailConfigStrategy;
use Xdp\Mail\Strategy\RandMailConfigStrategy;
use Xdp\Test\XdpTestCase;

class TestStrategy extends XdpTestCase
{
    public function testRandStrategy()
    {
        $this->runApp();
        $factory = new MailFactory();
        $mailer = $factory->connection((new RandMailConfigStrategy())->getConfig());
        $mailer->subject('test');
        $mailer->to('shiwenyuan@xmanlegal.com');
        $mailer->html("<h1>test</h1><p>xdp test</p>");
        var_dump($mailer->send());
    }


    public function testAppoint()
    {
        $this->runApp();
        $factory = new MailFactory();
        $mailer = $factory->connection((new IndexMailConfigStrategy())->getConfig());
        $mailer->subject('test');
        $mailer->to('shiwenyuan@xmanlegal.com');
        $mailer->html("<h1>test</h1><p>xdp test</p>");
        var_dump($mailer->send());
    }
    public function testPullStrategy()
    {
        $this->runApp();
        $factory = new MailFactory();
        $mailer = $factory->connection((new PollMailConfigStrategy())->getConfig());
        $mailer->subject('test');
        $mailer->to('shiwenyuan@xmanlegal.com');
        $mailer->html("<h1>test</h1><p>xdp test</p>");
        var_dump($mailer->send());
    }
}