<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/1 13341007105@163.com
 * Time: 下午6:25
 */

namespace Xdp\Test\Mail;
require_once __DIR__ . "/../../vendor/autoload.php";


use PHPUnit\Framework\TestCase;
use Xdp\Mail\Adapter\SwiftMailAdapter;

class TestEmailAdapter extends TestCase
{
     public static $config = [
         'host' => 'smtp.mxhichina.com',
         'port'     => 25,
         'username' => 'admin@xmanlegal.com',
         'password' => '',
         'name' => '未来法律',
     ];

    public function testSwiftMailAdapter()
    {
        $swift = new SwiftMailAdapter(self::$config);
        $swift->from(self::$config['username'],self::$config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->text('test email');
        $swift->subject('test');
        $swift->send();
    }

    public function testSwiftMailAttachment()
    {
        $swift = new SwiftMailAdapter(self::$config);
        $swift->from(self::$config['username'],self::$config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->text('test email');
        $swift->subject('test');
        $swift->attachment("/Users/shiwenyuan/Desktop/软件采购与实施合同.docx");
        $swift->send();
    }

    public function testSwiftMailSendHtml()
    {
        $swift = new SwiftMailAdapter(self::$config);
        $swift->from(self::$config['username'],self::$config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->html('<h1>test email</h1>');
        $swift->subject('test');
        $swift->attachment("/Users/shiwenyuan/Desktop/软件采购与实施合同.docx");
        $swift->send();
    }
}