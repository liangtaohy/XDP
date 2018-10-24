<?php
require_once __DIR__ . "/../../vendor/autoload.php";

use Xdp\Miniprogram\Miniprogram;
use PHPUnit\Framework\TestCase;

class TestMiniProgram extends TestCase
{
    private $appid = 'wxc6b89ce6de231748';
    private $app_secret = '981f0d8163dd5a131bc111d54b246fed';


    public function testWxBizDataCrypt()
    {
        $encryptedData="QdGCfgPktJMILAY0ik6UEEyS92RhKJlx/VcX4+9WJB2dUwpaqXt3f3bywK2/u775OtaJHT43o5KVGA6nO0EF62Ef5F7xJhRT3g4xH+8yDvG/qa8mYV1HxyMt983MYaKbsoMu0aXmubO3oI9ZQUcpkB+m4GS9wAdMw1cx6HQW0OMROWfOcJKTgSjGAfA98BC/2EsmkfWLnD4d+iw3SqWzLaOleQS+jfHHomWprGnKZ9LRd2sEoNTysrPoOR3YHkhysB5w2/vgJLNRQ9D6BU2w707zEODdkWTtcA1nxJc/xwo/3P4gRzbIEzhLAtprpdLoammuueGWQD4GWgpoM+HvY9c1vP7GfGbh+out0KWyQdP7b687poFmks3Eb85Ks5kVqeQKfIINVq9+Lh69DkTd3QwO8aQmb/0SR6s9s+9HgUcZnsFU7RJmBSEYluWKIToR3P3beUcyUtgp/QYqhmOQBYtSS+oNXkbvqFMJLhDX91kgz+YTkGtldUpscTuXmGoQfWVLFOfx9PA4xJ3yfB9shh2FJr9zBgz7iIUsOmAS4KI=";

        $iv = 'TNS2jV5+vl6mVBE6lriP7g==';

        $code = "001bAavv0x84La1N74uv08Ybvv0bAavt";

        $pc = new Miniprogram($this->appid, $this->app_secret);

        $userinfo = $pc->getUserInfo($code);

        var_dump($userinfo);
        exit(0);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);

        $this->assertEquals(0, $errCode);
    }
}
