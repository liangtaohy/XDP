<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/10/29 13341007105@163.com
 * Time: 1:09 PM
 */

namespace Xdp\Utils\Traits;

use Xdp\Pipeline\Pipeline;

/**
 * Trait Middleware
 * @package Xdp\Utils\Traits
 */
trait Middleware
{
    /**
     * @var array
     */
    private $middleWares = [];

    /**
     * @param array $middlewares
     */
    public function setMiddleWares(array $middlewares)
    {
        $this->middleWares = $middlewares;
    }

    /**
     * @param $middleware
     */
    public function setMiddleWare($middleware)
    {
        if (is_array($middleware)) {
            $this->middleWares = array_merge($this->middleWares, $middleware);
        } else {
            $this->middleWares = array_merge($this->middleWares, [$middleware]);
        }
    }

    /**
     * @param $mobile
     * @return mixed
     */
    public function useMiddleWare($mobile)
    {
        return (new Pipeline(app()))
            ->send($mobile)
            ->through($this->middleWares)
            ->then(function ($mobile) {
                return $mobile;
            });
    }
}