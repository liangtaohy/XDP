<?php
namespace Xdp\Http;

use ArrayAccess;
use Xdp\Contract\Support\Arrayable;
use Xdp\Utils\Arr;
use Xdp\Utils\Str;
use Xdp\Utils\Traits\Macroable;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest implements Arrayable, ArrayAccess
{
    use Traits\InteractsWithContentTypes;
    use Traits\InteractsWithInput;
    use Macroable;

    /**
     * 解码后的JSON数据（来自Request的body）
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag|null
     */
    protected $json;

    /**
     * 创建http request
     *
     * @return static
     */
    public static function capture()
    {
        static::enableHttpMethodParameterOverride();

        return static::createFromBase(SymfonyRequest::createFromGlobals());
    }

    public static function createFromBase(SymfonyRequest $request)
    {
        if ($request instanceof static) {
            return $request;
        }

        $content = $request->content;

        $request = (new static)->duplicate(
            $request->query->all(), $request->request->all(), $request->attributes->all(),
            $request->cookies->all(), $request->files->all(), $request->server->all()
        );

        $request->content = $content;

        $request->request = $request->getInputSource();

        return $request;
    }

    /**
     * 获取http method
     *
     * @return string GET\POST\HEAD\DELETE\PUT...
     */
    public function method()
    {
        return $this->getMethod();
    }

    /**
     * 获取request scheme
     *
     * @return string
     */
    public function scheme()
    {
        return $this->getScheme();
    }

    public function baseUrl()
    {
        return $this->getBaseUrl();
    }

    public function port()
    {
        return $this->getPort();
    }

    /**
     * 返回http host
     *
     * @var string | null
     */
    public function host()
    {
        return $this->getHttpHost();
    }

    /**
     * 获取请求的content type
     *
     * @var string | null
     */
    public function contentType()
    {
        return $this->getContentType();
    }

    /**
     * 获取应用的root url
     *
     * @return string
     */
    public function root()
    {
        return rtrim($this->getSchemeAndHttpHost().$this->getBaseUrl(), '/');
    }

    /**
     * 获取不带query参数的url
     *
     * @return string
     */
    public function url()
    {
        return rtrim(preg_replace('/\?.*/', '', $this->getUri()), '/');
    }

    /**
     * 获取full url
     *
     * @return string
     */
    public function fullUrl()
    {
        $query = $this->getQueryString();

        $question = $this->getBaseUrl().$this->getPathInfo() == '/' ? '/?' : '?';

        return $query ? $this->url().$question.$query : $this->url();
    }

    /**
     * 获取full url，并追加额外的query参数
     *
     * @param  array  $query
     * @return string
     */
    public function fullUrlWithQuery(array $query)
    {
        $question = $this->getBaseUrl().$this->getPathInfo() == '/' ? '/?' : '?';

        return count($this->query()) > 0
            ? $this->url().$question.http_build_query(array_merge($this->query(), $query))
            : $this->fullUrl().$question.http_build_query($query);
    }

    /**
     * 获取url path
     *
     * @return string
     */
    public function path()
    {
        return $this->getPathInfo();
    }

    /**
     * 获取decoded后的url path
     *
     * @return string
     */
    public function decodedPath()
    {
        return rawurldecode($this->path());
    }

    /**
     * 通过index，获取path segment。index从1开始。若不存在，则返回default值。
     *
     * @param  int  $index
     * @param  string|null  $default
     * @return string|null
     */
    public function segment($index, $default = null)
    {
        return Arr::get($this->segments(), $index - 1, $default);
    }

    /**
     * 获取所有的segment
     *
     * @return array
     */
    public function segments()
    {
        $segments = explode('/', $this->decodedPath());

        return array_values(array_filter($segments, function ($value) {
            return $value !== '';
        }));
    }

    /**
     * 检查url是否匹配某个pattern
     *
     * @param  dynamic  $patterns
     * @return bool
     */
    public function is(...$patterns)
    {
        foreach ($patterns as $pattern) {
            if (Str::is($pattern, $this->decodedPath())) {
                return true;
            }
        }

        return false;
    }

    /**
     * full url是否匹配某个pattern
     *
     * @param  dynamic  $patterns
     * @return bool
     */
    public function fullUrlIs(...$patterns)
    {
        $url = $this->fullUrl();

        foreach ($patterns as $pattern) {
            if (Str::is($pattern, $url)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 检查是否为ajax请求
     *
     * @note 仅在X-Requested-With请求头被设置时才有效。其它情况下，默认返回false。
     * @see http://en.wikipedia.org/wiki/List_of_Ajax_frameworks#JavaScript
     * @return bool
     */
    public function ajax()
    {
        return $this->isXmlHttpRequest();
    }

    /**
     * 检查是否为Pjax请求
     *
     * @note pjax是对ajax + pushState的封装，同时支持缓存和本地存储。在发起ajax请求时，地址栏url会改变，但不会刷新全页面。该特性是HTML5特性：pushState和replaceState。
     *
     * @return bool
     */
    public function pjax()
    {
        return $this->headers->get('X-PJAX') == true;
    }

    /**
     * 检查请求是否为HTTPS
     *
     * @return bool
     */
    public function secure()
    {
        return $this->isSecure();
    }

    /**
     * 获取客户端ip
     *
     * @return string
     */
    public function ip()
    {
        return $this->getClientIp();
    }

    /**
     * 获取客户端的所有ip地址
     *
     * @return array
     */
    public function ips()
    {
        return $this->getClientIps();
    }

    /**
     * 获取User-Agent
     *
     * @return string
     */
    public function userAgent()
    {
        return $this->headers->get('User-Agent');
    }

    /**
     * 获取请求的input source
     *
     * @return \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected function getInputSource()
    {
        if ($this->isJson()) {
            return $this->json();
        }

        return $this->getRealMethod() == 'GET' ? $this->query : $this->request;
    }

    /**
     * 替换input数据
     *
     * @param  array  $input
     * @return \Xdp\Http\Request
     */
    public function replace(array $input)
    {
        $this->getInputSource()->replace($input);

        return $this;
    }

    /**
     * 获取请求的payload并转为json对象
     *
     * @param string $key
     * @param mixed $default
     * @return mixed|null|ParameterBag
     */
    public function json($key = null, $default = null)
    {
        if (! isset($this->json)) {
            $this->json = new ParameterBag((array) json_decode($this->getContent(), true));
        }

        if (is_null($key)) {
            return $this->json;
        }

        return data_get($this->json->all(), $key, $default);
    }

    /**
     * 设置json payload
     *
     * @param  \Symfony\Component\HttpFoundation\ParameterBag  $json
     * @return $this
     */
    public function setJson($json)
    {
        $this->json = $json;

        return $this;
    }

    /**
     * 获取所有的请求数据
     *
     * @return array
     */
    public function toArray()
    {
        return $this->all();
    }

    /**
     * 检查offset是否存在
     *
     * @param  string  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists(
            $offset, $this->all() + $this->route()->parameters()
        );
    }

    /**
     * Get the value at the given offset.
     *
     * @param  string  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * Set the value at the given offset.
     *
     * @param  string  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->getInputSource()->set($offset, $value);
    }

    /**
     * Remove the value at the given offset.
     *
     * @param  string  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->getInputSource()->remove($offset);
    }

    /**
     * Check if an input element is set on the request.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return ! is_null($this->__get($key));
    }

    /**
     * Get an input element from the request.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->all())) {
            return data_get($this->all(), $key);
        }

        return null;
        //return $this->route($key);
    }
}
