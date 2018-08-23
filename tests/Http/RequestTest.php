<?php
/**
 * Created by PhpStorm.
 * User: xlegal
 * Date: 2018/8/23
 * Time: 下午6:02
 */

namespace Xdp\Test\Http;

require __DIR__.'/../../vendor/autoload.php';

use Mockery;
use PHPUnit\Framework\TestCase;
use Xdp\Http\Request;

class RequestTest extends TestCase
{
    public function testMethod()
    {
        $request = Request::create('', 'GET');
        $this->assertEquals('GET', $request->method());
        $request = Request::create('', 'POST');
        $this->assertEquals('POST', $request->method());
        $request = Request::create('', 'HEAD');
        $this->assertEquals('HEAD', $request->method());
        $request = Request::create('', 'PUT');
        $this->assertEquals('PUT', $request->method());
        $request = Request::create('', 'DELETE');
        $this->assertEquals('DELETE', $request->method());
        $request = Request::create('', 'OPTIONS');
        $this->assertEquals('OPTIONS', $request->method());
    }

    public function testRoot()
    {
        $request = Request::create('http://www.example.com/test/1/2/1.php');
        $this->assertEquals('http://www.example.com', $request->root());
    }

    public function testPath()
    {
        $request = Request::create('', 'GET');
        $this->assertEquals('/', $request->getPathInfo());

        $request = Request::create('/foo/bar', 'GET');
        $this->assertEquals('/foo/bar', $request->path());
    }

    public function testDecodedPathMethod()
    {
        $request = Request::create('/foo%20bar');
        $this->assertEquals('/foo bar', $request->decodedPath());
    }

    public function testSegmentMethod()
    {
        $request = Request::create('/foo/bar', 'GET');
        $this->assertEquals('foo', $request->segment(1));
        $this->assertEquals('bar', $request->segment(2));
        $this->assertEquals('null', $request->segment(3, 'null'));

    }

    public function testSegmentsMethod()
    {
        $request = Request::create('/foo/bar/music');
        $this->assertEquals(['foo', 'bar', 'music'], $request->segments());

        $request = Request::create('');
        $this->assertEquals([], $request->segments());
    }

    public function testUrlMethod()
    {
        $request = Request::create('/foo/bar?user=lotus&age=18#money=1000000000');
        $this->assertEquals('http://localhost/foo/bar', $request->url());

        $request = Request::create('http://example.com/foo/bar?user=lotus&age=18#money=1000000000');
        $this->assertEquals('http://example.com/foo/bar', $request->url());
    }

    public function testFullUrlMethod()
    {
        $request = Request::create('http://example.com/foo/bar?user=lotus&age=18#money=1000000000', 'GET');
        $this->assertEquals('http://example.com/foo/bar?age=18&user=lotus', $request->fullUrl());

        $request = Request::create('http://example.com/foo/bar');
        $this->assertEquals('http://example.com/foo/bar', $request->fullUrl());

        $request = Request::create('http://example.com?name=lotushy');
        $this->assertEquals('http://example.com/?name=lotushy', $request->fullUrl());

        $request = Request::create('http://example.com/foo/bar?name=lotushy');
        $this->assertEquals('http://example.com/foo/bar?name=lotushy&you=bitch', $request->fullUrlWithQuery(['you' => 'bitch']));

        $request = Request::create('/', 'GET', ['developer' => ['name' => 'Taylor', 'age' => 18]]);
        $this->assertEquals('http://localhost/?developer[name]=Taylor&developer[age]=18', rawurldecode($request->fullUrl()));
        $this->assertEquals('http://localhost/?developer%5Bname%5D=Taylor&developer%5Bage%5D=18', $request->fullUrl());
    }

    public function testIsMethod()
    {
        $request = Request::create('/foo/bar', 'GET');
        $this->assertFalse($request->is('foo*'));
        $this->assertTrue($request->is('/foo*'));
        $this->assertFalse($request->is('bar*'));
        $this->assertTrue($request->is('*bar*'));
    }

    public function testFullUrlIsMethod()
    {
        $request = Request::create('http://example.com/foo/bar?name=lotushy');
        $this->assertFalse($request->fullUrlIs('example.com*'));
        $this->assertTrue($request->fullUrlIs('http://example.com*'));
    }

    public function testAjaxMethod()
    {
        $request = Request::create('', 'GET');
        $this->assertFalse($request->ajax());

        $request = Request::create($uri='http://example.com/foo/bar?name=lotushy', $method='GET', [], [], [], ['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'], '{}');
        $this->assertTrue($request->ajax());

        $request = Request::create('/', 'POST');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $this->assertTrue($request->ajax());

        $request->headers->set('X-Requested-With', '');
        $this->assertFalse($request->ajax());
    }

    public function testPjaxMethod()
    {
        $request = Request::create('/', 'GET', [], [], [], ['HTTP_X_PJAX' => 'true'], '{}');
        $this->assertTrue($request->pjax());
        $request->headers->set('X-PJAX', 'false');
        $this->assertTrue($request->pjax());
        $request->headers->set('X-PJAX', null);
        $this->assertFalse($request->pjax());
        $request->headers->set('X-PJAX', '');
        $this->assertFalse($request->pjax());
    }

    public function testSecureMethod()
    {
        $request = Request::create('http://example.com', 'GET');
        $this->assertFalse($request->secure());
        $request = Request::create('https://example.com', 'GET');
        $this->assertTrue($request->secure());
    }

    public function testUserAgentMethod()
    {
        $request = Request::create('/', 'GET', [], [], [], ['HTTP_USER_AGENT' => 'xdp-ua'], '{}');
        $this->assertEquals('xdp-ua', $request->userAgent());
    }

    public function testHasMethod()
    {
        $request = Request::create('/', 'GET', ['name' => 'Taylor', 'age' => '', 'city' => null]);
        $this->assertTrue($request->has('name'));
        $this->assertTrue($request->has('age'));
        $this->assertTrue($request->has('city'));
        $this->assertFalse($request->has('foo'));
        $this->assertFalse($request->has('name', 'email'));

        $request = Request::create('/', 'GET', ['name' => 'Taylor', 'email' => 'foo']);
        $this->assertTrue($request->has('name'));
        $this->assertTrue($request->has('name', 'email'));

        $request = Request::create('/', 'GET', ['foo' => ['bar', 'bar']]);
        $this->assertTrue($request->has('foo'));
        echo PHP_EOL . $request->fullUrl() . PHP_EOL; // http://localhost/?foo%5B0%5D=bar&foo%5B1%5D=bar
        echo PHP_EOL . rawurldecode($request->fullUrl()). PHP_EOL; // http://localhost/?foo[0]=bar&foo[1]=bar

        $request = Request::create('/', 'GET', ['foo' => '', 'bar' => null]);
        $this->assertTrue($request->has('foo'));
        $this->assertTrue($request->has('bar'));

        $request = Request::create('/', 'GET', ['foo' => ['bar' => null, 'baz' => '']]);
        $this->assertTrue($request->has('foo.bar'));
        $this->assertTrue($request->has('foo.baz'));

        $request = Request::create('http://localhost/?foo[0]=bar&foo[1]=bar', 'GET');
        $this->assertTrue($request->has('foo'));
    }

    public function testHasAnyMethod()
    {
        $request = Request::create('/', 'GET', ['name' => 'Taylor', 'age' => '', 'city' => null]);
        $this->assertTrue($request->hasAny('name'));
        $this->assertTrue($request->hasAny('age'));
        $this->assertTrue($request->hasAny('city'));
        $this->assertFalse($request->hasAny('foo'));
        $this->assertTrue($request->hasAny('name', 'email'));
        $this->assertTrue($request->hasAny(['name', 'email']));

        $request = Request::create('/', 'GET', ['name' => 'Taylor', 'email' => 'foo']);
        $this->assertTrue($request->hasAny('name', 'email'));
        $this->assertFalse($request->hasAny('surname', 'password'));
        $this->assertFalse($request->hasAny(['surname', 'password']));

        $request = Request::create('/', 'GET', ['foo' => ['bar' => null, 'baz' => '']]);
        $this->assertTrue($request->hasAny('foo.bar'));
        $this->assertTrue($request->hasAny('foo.baz'));
        $this->assertFalse($request->hasAny('foo.bax'));
        $this->assertTrue($request->hasAny(['foo.bax', 'foo.baz']));
    }

    public function testFilledMethod()
    {
        $request = Request::create('/', 'GET', ['name' => 'Taylor', 'age' => '', 'city' => null]);
        $this->assertTrue($request->filled('name'));
        $this->assertFalse($request->filled('age'));
        $this->assertFalse($request->filled('city'));
        $this->assertFalse($request->filled('foo'));
        $this->assertFalse($request->filled('name', 'email'));

        $request = Request::create('/', 'GET', ['name' => 'Taylor', 'email' => 'foo']);
        $this->assertTrue($request->filled('name'));
        $this->assertTrue($request->filled('name', 'email'));

        //test arrays within query string
        $request = Request::create('/', 'GET', ['foo' => ['bar', 'baz']]);
        $this->assertTrue($request->filled('foo'));

        $request = Request::create('/', 'GET', ['foo' => ['bar' => 'baz']]);
        $this->assertTrue($request->filled('foo.bar'));
    }

    public function testFilledAnyMethod()
    {
        $request = Request::create('/', 'GET', ['name' => 'Taylor', 'age' => '', 'city' => null]);

        $this->assertTrue($request->anyFilled(['name']));
        $this->assertTrue($request->anyFilled('name'));

        $this->assertFalse($request->anyFilled(['age']));
        $this->assertFalse($request->anyFilled('age'));

        $this->assertFalse($request->anyFilled(['foo']));
        $this->assertFalse($request->anyFilled('foo'));

        $this->assertTrue($request->anyFilled(['age', 'name']));
        $this->assertTrue($request->anyFilled('age', 'name'));

        $this->assertTrue($request->anyFilled(['foo', 'name']));
        $this->assertTrue($request->anyFilled('foo', 'name'));

        $this->assertFalse($request->anyFilled('age', 'city'));
        $this->assertFalse($request->anyFilled('age', 'city'));

        $this->assertFalse($request->anyFilled('foo', 'bar'));
        $this->assertFalse($request->anyFilled('foo', 'bar'));
    }

    public function testInputMethod()
    {
        $request = Request::create('/', 'GET', ['name' => 'lotushy']);
        $this->assertEquals('lotushy', $request->input('name'));
        $this->assertEquals('lotushy', $request['name']);
        $this->assertEquals('Bob', $request->input('foo', 'Bob'));

        $request = Request::create('/', 'GET', [], [], ['file' => new \Symfony\Component\HttpFoundation\File\UploadedFile(__FILE__, 'foo.php')]);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\File\UploadedFile', $request['file']);
    }

    public function testAllMethod()
    {
        $request = Request::create('/', 'GET', ['name' => 'Taylor', 'age' => null]);
        $this->assertEquals(['name' => 'Taylor', 'age' => null, 'email' => null], $request->all('name', 'age', 'email'));
        $this->assertEquals(['name' => 'Taylor'], $request->all('name'));
        $this->assertEquals(['name' => 'Taylor', 'age' => null], $request->all());

        $request = Request::create('/', 'GET', ['developer' => ['name' => 'Taylor', 'age' => null]]);
        $this->assertEquals(['developer' => ['name' => 'Taylor', 'skills' => null]], $request->all('developer.name', 'developer.skills'));
        $this->assertEquals(['developer' => ['name' => 'Taylor', 'skills' => null]], $request->all(['developer.name', 'developer.skills']));
        $this->assertEquals(['developer' => ['age' => null]], $request->all('developer.age'));
        $this->assertEquals(['developer' => ['skills' => null]], $request->all('developer.skills'));
        $this->assertEquals(['developer' => ['name' => 'Taylor', 'age' => null]], $request->all());
    }

    public function testKeysMethod()
    {
        $request = Request::create('/', 'GET', ['name' => 'Taylor', 'age' => null]);
        $this->assertEquals(['name', 'age'], $request->keys());

        $files = [
            'foo' => [
                'size' => 500,
                'name' => 'foo.jpg',
                'tmp_name' => __FILE__,
                'type' => 'blah',
                'error' => null,
            ],
        ];
        $request = Request::create('/', 'GET', [], [], $files);
        $this->assertEquals(['foo'], $request->keys());

        $request = Request::create('/', 'GET', ['name' => 'Taylor'], [], $files);
        $this->assertEquals(['name', 'foo'], $request->keys());
    }

    public function testOnlyMethod()
    {
        $request = Request::create('/', 'GET', ['name' => 'Taylor', 'age' => null]);
        $this->assertEquals(['name' => 'Taylor', 'age' => null], $request->only('name', 'age', 'email'));

        $request = Request::create('/', 'GET', ['developer' => ['name' => 'Taylor', 'age' => null]]);
        $this->assertEquals(['developer' => ['name' => 'Taylor']], $request->only('developer.name', 'developer.skills'));
        $this->assertEquals(['developer' => ['age' => null]], $request->only('developer.age'));
        $this->assertEquals([], $request->only('developer.skills'));
    }

    public function testExceptMethod()
    {
        $request = Request::create('/', 'GET', ['name' => 'Taylor', 'age' => 25]);
        $this->assertEquals(['name' => 'Taylor'], $request->except('age'));
        $this->assertEquals([], $request->except('age', 'name'));
    }

    public function testQueryMethod()
    {
        $request = Request::create('/', 'GET', ['name' => 'Taylor']);
        $this->assertEquals('Taylor', $request->query('name'));
        $this->assertEquals('Bob', $request->query('foo', 'Bob'));
        $all = $request->query(null);
        $this->assertEquals('Taylor', $all['name']);

        $request = Request::create('/', 'GET', ['developer' => ['name' => 'Taylor', 'age' => 18]]);
        $this->assertEquals(['name' => 'Taylor', 'age' => 18], $request->query('developer'));
    }

    public function testPostMethod()
    {
        $request = Request::create('/', 'POST', ['name' => 'Taylor']);
        $this->assertEquals('Taylor', $request->post('name'));
        $this->assertEquals('Bob', $request->post('foo', 'Bob'));
        $all = $request->post(null);
        $this->assertEquals('Taylor', $all['name']);
    }

    public function testCookieMethod()
    {
        $request = Request::create('/', 'GET', [], ['name' => 'Taylor']);
        $this->assertEquals('Taylor', $request->cookie('name'));
        $this->assertEquals('Bob', $request->cookie('foo', 'Bob'));
        $all = $request->cookie(null);
        $this->assertEquals('Taylor', $all['name']);
    }

    public function testHasCookieMethod()
    {
        $request = Request::create('/', 'GET', [], ['foo' => 'bar']);
        $this->assertTrue($request->hasCookie('foo'));
        $this->assertFalse($request->hasCookie('qu'));
    }

    public function testServerMethod()
    {
        $request = Request::create('/', 'GET', [], [], [], ['foo' => 'bar', 'dev'=>['name'=>'lotus', 'age'=>18]]);
        $this->assertEquals('bar', $request->server('foo'));
        $this->assertEquals('liang', $request->server('dev.name', 'liang'));
        $this->assertEquals('bar', $request->server('foo.doesnt.exist', 'bar'));
        $all = $request->server(null);
        $this->assertEquals('bar', $all['foo']);
    }

    public function testHeaderMethod()
    {
        $request = Request::create('/', 'GET', [], [], [], ['HTTP_DO_THIS' => 'foo']);
        $this->assertEquals('foo', $request->header('do-this'));
        $all = $request->header(null);
        $this->assertEquals('foo', $all['do-this'][0]);
    }
}