<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/8/21 13341007105@163.com
 * Time: 下午1:12
 */

namespace Xdp\Pipeline;


use Xdp\Contract\Pipeline\ProcessorInterface;

/**
 * 管道阻断器
 * Class SuspendProcessor
 * @package Xdp\Pipeline
 */
class SuspendProcessor implements ProcessorInterface
{

    /**
     * @var callable
     */
    private $check;

    /**
     * SuspendProcessor constructor.
     * @param callable $check 阻断函数
     */
    public function __construct(callable $check)
    {
        $this->check = $check;
    }

    /**
     * @param mixed $payload
     * @param callable[] ...$stages
     * @return mixed
     */
    public function process($payload, callable ...$stages)
    {
        $check = $this->check;

        foreach ($stages as $stage) {
            $payload = $stage($payload);

            if (true !== $check($payload)) {
                return $payload;
            }
        }
        return $payload;
    }
}