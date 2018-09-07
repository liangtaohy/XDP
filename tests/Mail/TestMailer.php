<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/6 13341007105@163.com
 * Time: 下午1:15
 */

namespace Xdp\Test\Mail;

require_once __DIR__ . "/../../vendor/autoload.php";

use Xdp\Mail\Mailer;
use Xdp\Test\XdpTestCase;

/**
 * Class TestMailer
 * @package Xdp\Test\Mail
 */
class TestMailer extends XdpTestCase
{


    /**
     * @throws \Xdp\Container\Exception\ContainerException
     * @throws \Xdp\Mail\Exception\MailException
     */
    public function testMailerSend()
    {
        $this->runApp();
        Mailer::sendTplMsg(
            'shiwenyuan@xmanlegal.com',
            '11',
            'test',
            '13341007105@163.com',
            '1013816137@qq.com',
            '/Users/shiwenyuan/Desktop/智能快递柜代理商合同.docx',
            ['name' => '石文远bbb']
        );
        Mailer::sendTplMsg(
            ['address' => 'shiwenyuan@xmanlegal.com', 'name' => 'xman石文远'],
            '11',
            'test',
            ['address' => '13341007105@163.com', 'name' => '163石文远'],
            ['address' => '1013816137@qq.com', 'name' => 'qq石文远'],
            ['path' => '/Users/shiwenyuan/Desktop/智能快递柜代理商合同.docx', 'name' => '石文远.docx'],
            ['name' => '石文远aaa']
        );
        Mailer::sendHtml(
            ['address' => 'shiwenyuan@xmanlegal.com', 'name' => 'xman石文远'],
            '<h1>shiwenyuan</h1>',
            'sendhtml',
            ['address' => '13341007105@163.com', 'name' => '163石文远'],
            ['address' => '1013816137@qq.com', 'name' => 'qq石文远'],
            ['path' => '/Users/shiwenyuan/Desktop/智能快递柜代理商合同.docx', 'name' => '石文远.docx']
        );
        Mailer::row(
            [
                [
                'to' => ['address' => ['shiwenyuan@xmanlegal.com','1013816317@qq.com']],
                'body' => '<h1>shiwenyuan01</h1>',
                'subject' => 'sendhtml',
                'cc' => ['address' => '13341007105@163.com', 'name' => '163石文远'],
                'attachment' => ['path' => '/Users/shiwenyuan/Desktop/智能快递柜代理商合同.docx', 'name' => '石文远.docx']
                ],
               [
                    'to' => ['address' => 'shiwenyuan@xmanlegal.com', 'name' => 'xman石文远'],
                    'body' => '<h1>shiwenyuan02</h1>',
                    'subject' => 'sendhtml',
                    'bcc' => ['address' => '1013816137@qq.com', 'name' => 'qq石文远'],
                    'attachment' => ['path' => '/Users/shiwenyuan/Desktop/智能快递柜代理商合同.docx', 'name' => '石文远.docx']
                ],
                [
                    'to' => ['address' => 'shiwenyuan@xmanlegal.com', 'name' => 'xman石文远'],
                    'body' => '<h1>shiwenyuan03</h1>',
                    'subject' => 'sendhtml',
                    'cc' => ['address' => '13341007105@163.com', 'name' => '163石文远'],
                    'attachment' => ['path' => '/Users/shiwenyuan/Desktop/智能快递柜代理商合同.docx', 'name' => '石文远.docx']
                ]
            ]
        );
    }
}
