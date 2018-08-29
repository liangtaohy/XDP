<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/28
 * Time: 下午4:06
 */

class Foo
{
    public function __construct()
    {
        echo __CLASS__ . PHP_EOL;
    }
}

class_alias(Foo::class, 'Bar');

spl_autoload_register(function($class) {
    echo "register $class" . PHP_EOL;
    return class_alias(Foo::class, $class);
});
$c = new Car;