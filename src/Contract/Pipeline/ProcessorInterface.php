<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/8/21 13341007105@163.com
 * Time: 上午11:11
 */

namespace Xdp\Contract\Pipeline;


/**
 * Interface ProcessorInterface
 * @package Xdp\Contract\Pipeline
 */
interface ProcessorInterface
{
    /**
     * Process the payload using multiple stages.
     *
     * @param mixed $payload
     *
     * @return mixed
     */
    public function process($payload);
}