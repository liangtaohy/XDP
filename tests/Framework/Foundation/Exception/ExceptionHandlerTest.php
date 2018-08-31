<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/30
 * Time: 下午1:03
 */

namespace Xdp\Test;

require __DIR__.'/../../../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Xdp\Framework\Foundation\Exception\ConcreteExceptionHandler as ExceptionHandler;
use Exception;
use Xdp\Http\Request;

class ExceptionHandlerTest extends TestCase
{
    public function testRenderException()
    {
        $e = new Exception("exception test", 100);
        $handler = new ExceptionHandler;
        $request = Request::create('/foo/bar', 'GET');
        $response = $handler->render($request, $e);
        $this->assertEquals($e->getCode(), $response->getOriginalContent()['code']);
        $this->assertEquals($e->getMessage(), $response->getOriginalContent()['message']);
    }
}
