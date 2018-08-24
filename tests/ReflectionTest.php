<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/24
 * Time: 下午5:12
 */

class CTest
{
    public function __construct()
    {
        echo __FUNCTION__ . PHP_EOL;
    }
}

class Example
{
    /**
     * hello world
     * @DocTag: it's a tag!
     */
    public function helloworld()
    {
        echo PHP_EOL . "hello, world!" . PHP_EOL;
    }

    public function count(int $a = 1, int $b = 3, CTest $c = null)
    {
        return $a + $b;
    }
}

function getDocComment($str, $tag = '')
{
    if (empty($tag))
    {
        return $str;
    }

    $matches = array();
    preg_match("/".$tag.":(.*)(\\r\\n|\\r|\\n)/U", $str, $matches);

    if (isset($matches[1]))
    {
        return trim($matches[1]);
    }

    return '';
}

$method = new ReflectionMethod(Example::class, 'helloworld');
var_dump($method);

var_dump($method->getDocComment());

$s = getDocComment($method->getDocComment(), '@DocTag');
echo $s;

$method = new ReflectionMethod(Example::class, 'count');

echo PHP_EOL;

var_dump($method->getParameters());

foreach($method->getParameters() as $key => $parameter) {
    if ($parameter->isDefaultValueAvailable()) {
        echo $parameter->name . " default value is " . $parameter->getDefaultValue() . PHP_EOL;
        echo $parameter->getDefaultValueConstantName() . PHP_EOL;
    }
}