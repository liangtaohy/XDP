<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/8/21 13341007105@163.com
 * Time: 上午10:17
 */
namespace Xdp\Pipeline;

use Xdp\Contract\Pipeline\PipeLineInterface;
use Xdp\Contract\Pipeline\ProcessorInterface;

/**
 * Class Pipeline 管道类
 * @package Xdp\Pipeline
 */
class Pipeline implements PipeLineInterface
{

    /**
     * @var ProcessorInterface|FingersCrossedProcessor
     */
    private $processor;
    /**
     * @var array|callable[]
     */
    private $stages = [];

    /**
     * Pipeline constructor.
     * @param ProcessorInterface|null $processor
     * @param callable[] ...$stages
     */
    public function __construct(ProcessorInterface $processor = null, callable ...$stages)
    {
        $this->processor = $processor ?? new FingersCrossedProcessor();
        $this->stages = $stages;
    }

    /**
     * @param callable $stage
     * @return PipeLine
     */
    public function pipe(callable $stage):PipeLineInterface
    {
        $pipeline = clone $this;
        $pipeline->stages[] = $stage;
        return $pipeline;
    }

    /**
     * @param $payload
     * @return mixed
     */
    public function process($payload)
    {
        return $this->processor->process($payload, ...$this->stages);
    }

    /**
     * @param $payload
     * @return mixed
     */
    public function __invoke($payload)
    {
        return $this->process($payload);
    }
}