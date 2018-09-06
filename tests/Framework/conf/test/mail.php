<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/9/3
 * Time: 上午9:57
 */

return  [
    'drivers' =>[
        'PhpMailer' => 'Xdp\Mail\Adapter\PhpMailerAdapter',
        'SwiftMail' => 'Xdp\Mail\Adapter\SwiftMailAdapter',
    ],
    'accounts' => [
        'admin' => [
            'host' => 'smtp.mxhichina.com',
            'swiftMailPort' => 25,
            'phpMailerPort' => 465,
            'username' => 'admin@xmanlegal.com',
            'password' => 'X2016Legal#www',
            'name' => '未来法律',
            'charset' => 'UTF-8',
        ],
        'admin01' => [
            'host' => 'smtp.mxhichina.com',
            'swiftMailPort' => 25,
            'phpMailerPort' => 465,
            'username' => 'admin01@xmanlegal.com',
            'password' => 'X2018Legal#www',
            'name' => '未来法律',
            'charset' => 'UTF-8',
        ],
    ],
];