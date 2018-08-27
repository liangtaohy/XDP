<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/8/21 13341007105@163.com
 * Time: 上午10:26
 */

namespace Xdp\Contract\Pipeline;


/**
 * Interface StageInterface
 * @package Xdp\Contract\Pipeline
 */
use Closure;

interface MiddlewareInterface
{
    /**
     * 中间件默认方法
     * 方法中必须实现$next($request) 否则则不会往下执行
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next);
}
