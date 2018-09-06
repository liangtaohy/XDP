<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/1 13341007105@163.com
 * Time: 下午6:25
 */

namespace Xdp\Test\Mail;
require_once __DIR__ . "/../../vendor/autoload.php";


use Xdp\Mail\Adapter\SwiftMailAdapter;
use Xdp\Test\XdpTestCase;

class TestSwiftMailAdapter extends XdpTestCase
{
    public function testSwiftMailAdapter()
    {
        $this->runApp();
        $config = config('mail.accounts.admin');

        $swift = new SwiftMailAdapter($config);
        $swift->from($config['username'],$config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->text('test email');
        $swift->subject('test');
        $swift->send();
    }

    public function testSwiftMailAttachment()
    {
        $this->runApp();

        $config = config('mail.accounts.admin');

        $swift = new SwiftMailAdapter($config);
        $swift->from($config['username'], $config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->text('test email');
        $swift->subject('test');
        $swift->attachment("/Users/shiwenyuan/Desktop/软件采购与实施合同.docx");
        $swift->send();
    }

    public function testSwiftMailSendHtml()
    {
        $this->runApp();

        $config = config('mail.accounts.admin');

        $swift = new SwiftMailAdapter($config);
        $swift->from($config['username'], $config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->html('<h1>test email</h1>');
        $swift->subject('test');
        $swift->attachment("/Users/shiwenyuan/Desktop/软件采购与实施合同.docx");
        $swift->send();
    }
}