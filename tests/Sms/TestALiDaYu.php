<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/25 13341007105@163.com
 * Time: 4:54 PM
 */

namespace Xdp\Test\Sms;
require_once __DIR__ . '/../../vendor/autoload.php';


use Xdp\Test\XdpTestCase;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;
class TestALiDaYu extends XdpTestCase
{
    /**
     * @throws \Exception
     */
    public function testSend()
    {

        $config = [
            'app_key' => '23379413',
            'app_secret' => '5623394aec68f68397699c5def7a5578',
            'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];


        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;

        $req->setRecNum('13341007105')
            ->setSmsParam([
//                "business_name" => "asdasda",
            ])
            ->setSmsFreeSignName("未来法律")
            ->setSmsTemplateCode("SMS_135026825");

        $resp = $client->execute($req);
        var_dump($resp);
    }
}