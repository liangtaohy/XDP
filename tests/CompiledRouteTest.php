<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/24
 * Time: 下午5:58
 */
namespace Xdp\Test;

require __DIR__.'/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCompiler;
use Symfony\Component\Routing\CompiledRoute;

class CompiledRouteTest extends TestCase
{
    public function testRouteCompile()
    {
        $route = new Route('/get', '');
    }
}