<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/8/21 13341007105@163.com
 * Time: 上午11:12
 */

namespace Xdp\Pipeline;


use Xdp\Contract\Pipeline\ProcessorInterface;

/**
 * Class FingersCrossedProcessor
 * @package Xdp\Pipeline
 */
class FingersCrossedProcessor implements ProcessorInterface
{

    /**
     * @param mixed $payload
     * @param callable[] ...$stages
     * @return mixed
     */
    public function process($payload, callable ...$stages)
    {
        foreach ($stages as $stage) {
            $payload = $stage($payload);
        }
        return $payload;
    }
}