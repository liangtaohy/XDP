<?php
/**
 * Kernel Interface
 * User: Liang Tao (liangtaohy@gmail.com)
 * Date: 2018/8/23
 * Time: 下午4:10
 */
namespace Xdp\Contract\Kernel;

interface Kernel
{
    /**
     * 处理incoming http request
     *
     * @param $request
     * @return mixed
     */
    public function handle($request);

    /**
     * 执行http request生命周期的任何最终操作
     *
     * @param $request
     * @param $response
     * @return mixed
     */
    public function terminate($request, $response);

    /**
     * 返回Xdp Application实例
     *
     * @return mixed
     */
    public function getApplication();
}