<?php
namespace Xdp\Test\Http;

require __DIR__.'/../../vendor/autoload.php';

use Mockery;
use JsonSerializable;
use Xdp\Contract\Support\Arrayable;
use Xdp\Contract\Support\Jsonable;
use Xdp\Contract\Support\Renderable;
use Xdp\Http\RedirectResponse;
use Xdp\Http\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Cookie;

class ResponseTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testResonpseConvertedAndHeaderSet()
    {
        $response = new Response(new ArrayableStub());
        $this->assertEquals('{"foo":"bar"}', $response->getContent());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));

        $response = new Response(new JsonableStub());
        $this->assertEquals('foo', $response->getContent());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));

        $response = new Response(new ArrayableAndJsonableStub());
        $this->assertEquals('{"foo":"bar"}', $response->getContent());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));

        $response = new Response;
        $response->setContent(["foo" => "bar"]);
        $this->assertEquals('{"foo":"bar"}', $response->getContent());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testResponseRender()
    {
        $render = Mockery::mock("Xdp\Contract\Support\Renderable");
        $render->shouldReceive("render")
            ->once()
            ->andReturn('foo');
        $response = new Response($render);
        $this->assertEquals('foo', $response->getContent());
    }

    public function testHeader()
    {
        $response = new Response;
        $this->assertNull($response->headers->get('foo'));
        $response->header('foo', 'bar');
        $this->assertEquals('bar', $response->headers->get('foo'));
        $response->header('foo', 'baz', $replace = false);
        $this->assertEquals('bar', $response->headers->get('foo'));
        $response->header('foo', 'baz', $replace = true);
        $this->assertEquals('baz', $response->headers->get('foo'));
    }

    public function testWithCookies()
    {
        $response = new Response;
        $this->assertCount(0, $response->headers->getCookies());
        $response->withCookie(new Cookie($name = 'foo', $value = 'bar'));
        $cookies = $response->headers->getCookies();
        $this->assertCount(1, $cookies);
        $this->assertEquals('foo', $cookies[0]->getName());
        $this->assertEquals('bar', $cookies[0]->getValue());
        $response->withCookie(new Cookie($name = 'sid', $value = 'testsid', $expire = '2019-08-23T03:55:55.000Z', $domain='xmanlegal.com'));
        $cookies = $response->headers->getCookies($format=\Symfony\Component\HttpFoundation\ResponseHeaderBag::COOKIES_FLAT);
        $this->assertCount(2, $cookies);
        $this->assertEquals('testsid', $cookies[1]->getValue());
        $this->assertEquals(strtotime('2019-08-23T03:55:55.000Z'), $cookies[1]->getExpiresTime());
        echo PHP_EOL . $response . PHP_EOL;
    }

    public function testGetOriginalContent()
    {
        $content = ['foo' => 'bar'];
        $response = new Response($content);
        $this->assertEquals($content, $response->getOriginalContent());
    }

    public function testGetOriginalContentFromPreviousResponse()
    {
        $prevResponse = new Response(['foo' => 'bar']);
        $res = new Response($prevResponse);
        $this->assertEquals(['foo' => 'bar'], $res->getOriginalContent());
    }

    public function testSetAndRetrieveStatusCode()
    {
        $res = new Response('foo');
        $res->setStatusCode(404, $text = 'NOT Found');
        $this->assertEquals(404, $res->getStatusCode());
    }
}

class ArrayableStub implements Arrayable
{
    public function toArray()
    {
        return ['foo' => 'bar'];
    }
}

class JsonableStub implements Jsonable
{
    public function toJson($options = 0)
    {
        return 'foo';
    }
}

class ArrayableAndJsonableStub implements Arrayable, Jsonable
{
    public function toJson($options = 0)
    {
        return '{"foo":"bar"}';
    }

    public function toArray()
    {
        return [];
    }
}

class JsonSerializableStub implements JsonSerializable
{
    public function jsonSerialize()
    {
        return ['foo' => 'bar'];
    }
}