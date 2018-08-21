<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/8/21 13341007105@163.com
 * Time: 上午10:23
 */

namespace Xdp\Contract\Pipeline;

/**
 * Interface PipeLineInterface
 * @package Xdp\Contract\Pipeline
 */
interface PipeLineInterface extends StageInterface
{
    /**
     * @param callable $operation
     * @return PipeLineInterface
     */
    public function pipe(callable $operation): PipelineInterface;
}