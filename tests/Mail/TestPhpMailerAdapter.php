<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/2 13341007105@163.com
 * Time: 下午5:08
 */

namespace Xdp\Test\Mail;
use PHPUnit\Framework\TestCase;
use Xdp\Mail\Adapter\PhpMailerAdapter;

require_once __DIR__ . "/../../vendor/autoload.php";

class TestPhpMailerAdapter extends TestCase
{
    public static $config = [
        'host' => 'smtp.mxhichina.com',
        'port'     => 465,
        'username' => 'admin@xmanlegal.com',
        'password' => '',
        'name' => '未来法律',
        'charset'    => 'UTF-8',
        'SMTPDebug'  => 0,// 启用SMTP调试功能 0关闭
        'SMTPAuth'   => true,// 启用 SMTP 验证功能
        'SMTPSecure' => 'ssl',// 安全协议
    ];

    public function testMailAdapter()
    {
        $swift = new PhpMailerAdapter(self::$config);
        $swift->from(self::$config['username'],self::$config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->text('test email');
        $swift->subject('test');
        $swift->send();
    }


    public function testMailAttachment()
    {
        $swift = new PhpMailerAdapter(self::$config);
        $swift->from(self::$config['username'],self::$config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->text('test email');
        $swift->subject('test');
        $swift->attachment("/Users/shiwenyuan/Desktop/软件采购与实施合同.docx");
        $swift->send();
    }

    public function testMailSendHtml()
    {
        $swift = new PhpMailerAdapter(self::$config);
        $swift->from(self::$config['username'],self::$config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->html('<h1>xdp</h1><p>test email</p>');
        $swift->subject('test');
        $swift->send();
    }

    public function testStaticClass()
    {
        $swift = PhpMailerAdapter::getInstance(self::$config);

        $swift->from(self::$config['username'],self::$config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->html('<h1>xdp</h1><p>test email</p>');
        $swift->subject('test');
        $swift->send();
    }

    public function testToIsArray()
    {
        $GLOBALS['LOG'] = [
            'log_file' => __DIR__.'/logs/mail.log',
            'log_level' => \XdpLog\MeLog::LOG_LEVEL_ALL
        ];
        $swift = PhpMailerAdapter::getInstance(self::$config);

        $swift->from(self::$config['username'],self::$config['name']);
        $swift->to(['1013816137@qq.com','13341007105@163.com'],'石文远');
        $swift->html('<h1>xdp</h1><p>test email</p>');
        $swift->subject('test');
        $swift->send();
    }

    public function testBccIsArray()
    {
        $GLOBALS['LOG'] = [
            'log_file' => __DIR__.'/logs/mail.log',
            'log_level' => \XdpLog\MeLog::LOG_LEVEL_ALL
        ];
        $swift = PhpMailerAdapter::getInstance(self::$config);

        $swift->from(self::$config['username'],self::$config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->bcc(['shiwenyuan@xmanlegal.com','13341007105@163.com'],'bcc');
        $swift->html('<h1>xdp</h1><p>test email</p>');
        $swift->subject('test');
        $swift->send();

        $swift->from(self::$config['username'],self::$config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->bcc(['shiwenyuan@xmanlegal.com'=>'bcc','13341007105@163.com'=>'bcc163']);
        $swift->html('<h1>xdp</h1><p>test email</p>');
        $swift->subject('test');
        $swift->send();
    }


    public function testCcIsArray()
    {
        $GLOBALS['LOG'] = [
            'log_file' => __DIR__.'/logs/mail.log',
            'log_level' => \XdpLog\MeLog::LOG_LEVEL_ALL
        ];
        $swift = PhpMailerAdapter::getInstance(self::$config);

        $swift->from(self::$config['username'],self::$config['name']);
        $swift->to(['1013816137@qq.com'],'石文远');
        $swift->cc(['shiwenyuan@xmanlegal.com','13341007105@163.com'],'cc');
        $swift->html('<h1>xdp</h1><p>test email</p>');
        $swift->subject('test');
        $swift->send();

        $swift->from(self::$config['username'],self::$config['name']);
        $swift->to(['1013816137@qq.com'],'石文远');
        $swift->cc(['shiwenyuan@xmanlegal.com'=>'xmanlegal','13341007105@163.com'=>'email163']);
        $swift->html('<h1>xdp</h1><p>test email</p>');
        $swift->subject('test');
        $swift->send();
    }


}