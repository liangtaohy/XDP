<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/3 13341007105@163.com
 * Time: 下午1:27
 */

namespace Xdp\Mail;


use Xdp\Contract\Mail\Factory;
use Xdp\Mail\Adapter\PhpMailerAdapter;
use Xdp\Mail\Adapter\SwiftMailAdapter;

class MailFactory implements Factory
{
    /*
     * config demo
     *
     * public static $config = [
        'PHPMailer' => [
            'admin' => [
                'host' => 'smtp.mxhichina.com',
                'port' => 465,
                'username' => 'admin@xmanlegal.com',
                'password' => '',
                'name' => '未来法律',
                'charset' => 'UTF-8',
                'SMTPDebug' => 0,// 启用SMTP调试功能 0关闭
                'SMTPAuth' => true,// 启用 SMTP 验证功能
                'SMTPSecure' => 'ssl',// 安全协议
            ],
            'admin01' => [
                'host' => 'smtp.mxhichina.com',
                'port' => 465,
                'username' => 'admin@xmanlegal.com',
                'password' => '',
                'name' => '未来法律',
                'charset' => 'UTF-8',
                'SMTPDebug' => 0,// 启用SMTP调试功能 0关闭
                'SMTPAuth' => true,// 启用 SMTP 验证功能
                'SMTPSecure' => 'ssl',// 安全协议
            ],
        ],
        'SwiftMail' => [
            'admin' => [
                'host' => 'smtp.mxhichina.com',
                'port' => 465,
                'username' => 'admin@xmanlegal.com',
                'password' => '',
                'name' => '未来法律',
                'charset' => 'UTF-8',
                'SMTPDebug' => 0,// 启用SMTP调试功能 0关闭
                'SMTPAuth' => true,// 启用 SMTP 验证功能
                'SMTPSecure' => 'ssl',// 安全协议
            ],
            'admin01' => [
                'host' => 'smtp.mxhichina.com',
                'port' => 465,
                'username' => 'admin@xmanlegal.com',
                'password' => '',
                'name' => '未来法律',
                'charset' => 'UTF-8',
                'SMTPDebug' => 0,// 启用SMTP调试功能 0关闭
                'SMTPAuth' => true,// 启用 SMTP 验证功能
                'SMTPSecure' => 'ssl',// 安全协议
            ],
        ]
    ];*/

    public function mailer($driver = 'SwiftMailer', $user = null)
    {
        $config = config('mail' . $driver . $user);

        switch ($driver) {
            case 'PHPMailer':
                $adapter = PhpMailerAdapter::getInstance($config);
                break;
            case 'SwiftMail':
                $adapter = SwiftMailAdapter::getInstance($config);
                break;
        }
        return $adapter;
    }
}