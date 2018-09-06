<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/2 13341007105@163.com
 * Time: 下午5:08
 */

namespace Xdp\Test\Mail;
use Xdp\Mail\Adapter\PhpMailerAdapter;
use Xdp\Test\XdpTestCase;

require_once __DIR__ . "/../../vendor/autoload.php";

class TestPhpMailerAdapter extends XdpTestCase
{
    public function testMailAdapter()
    {
        $this->runApp();

        $config = config('mail.accounts.admin');

        $swift = new PhpMailerAdapter($config);
        $swift->from($config['username'], $config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->text('test email');
        $swift->subject('test');
        $swift->send();
    }


    public function testMailAttachment()
    {
        $this->runApp();

        $config = config('mail.accounts.admin');

        $swift = new PhpMailerAdapter($config);
        $swift->from($config['username'], $config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->text('test email');
        $swift->subject('test');
        $swift->attachment("/Users/shiwenyuan/Desktop/软件采购与实施合同.docx");
        $swift->send();
    }

    public function testMailSendHtml()
    {
        $this->runApp();

        $config = config('mail.accounts.admin');

        $swift = new PhpMailerAdapter($config);
        $swift->from($config['username'], $config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->html('<h1>xdp</h1><p>test email</p>');
        $swift->subject('test');
        $swift->send();
    }

    public function testStaticClass()
    {
        $this->runApp();

        $config = config('mail.accounts.admin');

        $swift = PhpMailerAdapter::getInstance($config);

        $swift->from($config['username'],$config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->html('<h1>xdp</h1><p>test email</p>');
        $swift->subject('test');
        $swift->send();
    }

    public function testToIsArray()
    {
        $this->runApp();

        $config = config('mail.accounts.admin');

        $swift = PhpMailerAdapter::getInstance($config);

        $swift->from($config['username'],$config['name']);
        $swift->to(['1013816137@qq.com','13341007105@163.com'],'石文远');
        $swift->html('<h1>xdp</h1><p>test email</p>');
        $swift->subject('test');
        $swift->send();
    }

    public function testBccIsArray()
    {
        $this->runApp();

        $config = config('mail.accounts.admin');

        $swift = PhpMailerAdapter::getInstance($config);

        $swift->from($config['username'],$config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->bcc(['shiwenyuan@xmanlegal.com','13341007105@163.com'],'bcc');
        $swift->html('<h1>xdp</h1><p>test email</p>');
        $swift->subject('test');
        $swift->send();

        $swift->from($config['username'],$config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->bcc(['shiwenyuan@xmanlegal.com'=>'bcc','13341007105@163.com'=>'bcc163']);
        $swift->html('<h1>xdp</h1><p>test email</p>');
        $swift->subject('test');
        $swift->send();
    }


    public function testCcIsArray()
    {
        $this->runApp();

        $config = config('mail.accounts.admin');

        $swift = PhpMailerAdapter::getInstance($config);

        $swift->from($config['username'],$config['name']);
        $swift->to(['1013816137@qq.com'],'石文远');
        $swift->cc(['shiwenyuan@xmanlegal.com','13341007105@163.com'],'cc');
        $swift->html('<h1>xdp</h1><p>test email</p>');
        $swift->subject('test');
        $swift->send();

        $swift->from($config['username'],$config['name']);
        $swift->to(['1013816137@qq.com'],'石文远');
        $swift->cc(['shiwenyuan@xmanlegal.com'=>'xmanlegal','13341007105@163.com'=>'email163']);
        $swift->html('<h1>xdp</h1><p>test email</p>');
        $swift->subject('test');
        $swift->send();
    }


}