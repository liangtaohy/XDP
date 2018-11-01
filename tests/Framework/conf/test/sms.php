<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/26 13341007105@163.com
 * Time: 1:10 PM
 */


return [
    'default_driver' => 'aliyun',
    'accounts' => [
        'aliyun' => [
            'app_key' => '23406112',
            'app_secret' => 'ba085914e727ee9c88f25cb2a5bd0cfa',
            'sandbox' => true,
            'sign_name' => '未来法律',
            'vcode_tpl_code' => 'SMS_76550018',
        ],
        'qcloud' => [
            'app_key' => 1400093387,
            'app_secret' => 'cf6d82816622e48efaf2909a43134ff9',
            'sign_name' => '未来法律',
            'vcode_tpl_code' => 122447,
        ]
    ],
    'voice_option' => [
        'appid' => '20751',
        'appkey' =>'cd84276f4509991459c8d7176761afba',
        'sign_type'=>'normal',
        'server'=>'https://api.mysubmail.com/',
]
];
