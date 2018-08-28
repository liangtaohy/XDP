<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/28
 * Time: 下午5:48
 */
require __DIR__.'/../../vendor/autoload.php';

use Xdp\Framework\AliasLoader;
use Xdp\Framework\Facades;
use Xdp\Container\Container;

use PHPUnit\Framework\TestCase;

class AliasLoaderTest extends TestCase
{
    public function testAlias()
    {
        $aliases = [
            'HelloTest' => Xdp\Test\Framework\AliasTest\HelloTest::class,
            'Boobs' => Xdp\Test\Framework\AliasTest\BoobsFacadesStub::class,
        ];

        $loader = new AliasLoader($aliases);
        $loader->register();

        $boobs = new HelloTest;
        $this->assertEquals('Hello, world!', $boobs->say());

        $app = new Container();
        $app->make(Xdp\Test\Framework\AliasTest\Boobs::class, 'boobs', Xdp\Test\Framework\AliasTest\Boobs::class);
        Facades::setApplication($app);
        $this->assertEquals('Big boobs', Boobs::say());
        $this->assertEquals('Buy:Shoe', Boobs::buy('Shoe'));
    }
}