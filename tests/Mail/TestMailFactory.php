<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/3 13341007105@163.com
 * Time: 下午4:33
 */

namespace Xdp\Test\Mail;

require_once __DIR__ . "/../../vendor/autoload.php";


use PHPUnit\Framework\TestCase;
use Xdp\Mail\MailFactory;

class TestMailFactory extends TestCase
{


    public function testMailer()
    {

        $date = (new MailFactory())->mailer();
        var_dump($date);
    }
}