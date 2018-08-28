<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/28
 * Time: 下午6:00
 */

namespace Xdp\Test\Framework\AliasTest;

use Xdp\Framework\Facades;

class BoobsFacadesStub extends Facades
{
    public static function getFacadeAccessor()
    {
        return "boobs";
    }
}