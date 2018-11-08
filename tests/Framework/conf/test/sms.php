<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/26 13341007105@163.com
 * Time: 1:10 PM
 */


return [
    //|--------------------------------------默认服务方-------------------------------------------|
    'default_driver' => 'aliyun',
    //|--------------------------------------服务方配置列表----------------------------------------|
    'accounts' => [
        'aliyun' => [
            'app_key' => '23406112',
            'app_secret' => 'xxxx',
            'sandbox' => true,
            'sign_name' => '未来法律',
            'vcode_tpl_code' => 'SMS_76550018',
        ],
        'qcloud' => [
            'app_key' => 1400093387,
            'app_secret' => 'xxxx',
            'sign_name' => '未来法律',
            'vcode_tpl_code' => 122447,
        ]
    ],
    //|------------------------------------------语音短信服务方------------------------------------|
    'voice_option' => [
        'appid' => '20751',
        'appkey' =>'xxxx',
        'sign_type'=>'normal',
        'server'=>'https://api.mysubmail.com/',
    ]
];
