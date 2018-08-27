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

$a = 1;
$b = 3;

$c = function ($a, $b, string $x = "hello") {
    return $a + $b;
};

$method = new ReflectionFunction($c);

var_dump($method->getParameters());

$parameters = [];

echo PHP_EOL;

foreach ($method->getParameters() as $key => $parameter) {
    echo "class: " . $parameter->getClass()->name . PHP_EOL;
    $parameters[$parameter->name] = ${$parameter->name};
    if (is_null($parameters[$parameter->name])) {
        if ($parameter->isDefaultValueAvailable()) {
            $parameters[$parameter->name] = $parameter->getDefaultValue();
        }
    }
}

var_dump($parameters);

$result = $c(...array_values($parameters));
echo "result of function c is " . $result . PHP_EOL;
$result = call_user_func_array($c, $parameters);
echo "result of function c is " . $result . PHP_EOL;


$url = "http://example.com/{foo}/{bar}?name=lotus";

preg_match_all("/\{(.*?)\}/", $url, $match);
var_dump($match);

$url = "http://example.com/{foo}/{bar?}?name=lotus";

preg_match_all("/\{(.*?)\}/", $url, $match);
var_dump($match);
var_dump(array_flip($match[1]));

$match = array_map(function ($m) { return trim($m, '?'); }, $match[1]);
var_dump($match);

$url = "http://example.com/foo/bar?name=lotus";

unset($match);
$int = preg_match_all("/\{(.*?)\}/", $url, $matches);
$match = array_map(function ($m) { return trim($m, '?'); }, $matches[1]);
var_dump($match);

$a = ['1', 'a'=>2, 10];
var_dump(array_slice($a, 1));

function test(array $methods)
{
    var_dump($methods);
}

test(['head', 'get']);
test('put');