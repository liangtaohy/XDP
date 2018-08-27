<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/8/24 13341007105@163.com
 * Time: 下午4:13
 */

namespace Xdp\Contract\Pipeline;

use Closure;

/**
 * Interface PipeLineInterface
 * @package Xdp\Contract\Pipeline
 */
interface PipeLineInterface
{
    /**
     * 将对象放入管道
     * @param $passable
     * @return mixed
     */
    function send($passable);

    /**
     * 放置需要通过的管道
     * @param $pipes
     * @return mixed
     */
    function through($pipes);

    /**
     * 触发管道
     * @param Closure $destination
     * @return mixed
     */
    function then(Closure $destination);

    /**
     * 指定管道model
     * @param string $method
     * @return mixed
     */
    function via(string $method);
}