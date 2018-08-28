<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/28
 * Time: 下午6:02
 */

namespace Xdp\Test\Framework\AliasTest;


class Boobs
{
    public function say()
    {
        return "Big boobs";
    }

    public function buy($product)
    {
        return "Buy:{$product}";
    }
}