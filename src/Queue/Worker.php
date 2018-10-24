<?php
/**
 * Worker For handling jobs in queue
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/12
 * Time: 下午4:37
 */
namespace Xdp\Queue;

class Worker
{
    /**
     * 未定义状态
     */
    const S_UNDEFINED = 0;

    /**
     * 运行中
     */
    const S_RUNNING = 1;

    /**
     * 暂停中
     */
    const S_PAUSED = 2;

    /**
     * 已停止
     */
    const S_STOPPED = 3;

    /**
     * 已强制杀死
     */
    const S_KILLED = 4;

    /**
     * worker编号
     * @var
     */
    private $id = 0;

    /**
     * 状态
     * @var int
     */
    private $state = 0; // worker状态：running - 1, paused - 2, stopped - 3

    /**
     * 已处理的任务总数
     *
     * @var int
     */
    private $jobs = 0;

    /**
     * 成功处理的任务总数
     *
     * @var int
     */
    private $successJobs = 0;

    /**
     * 失败的任务数
     *
     * @var int
     */
    private $failedJobs = 0;

    /**
     * 总运行时长（毫秒）
     *
     * @var int
     */
    private $totalTime = 0;

    /**
     * Job平均耗时
     *
     * @var int
     */
    private $avgTime = 0;

    /**
     * Job最大耗时
     *
     * @var int
     */
    private $maxTime = 0;

    /**
     * Job最小耗时
     *
     * @var int
     */
    private $minTime = 0;

    /**
     * Job最大可执行时间, 超过此时间, 则强制停止Job
     *
     * @note 0, 无限制
     * @var int
     */
    private $maxTimeOut = 0;

    /**
     * 队列名称
     *
     * @var
     */
    private $queue;

    public function __construct($db, $queue)
    {
        $this->queue = $queue;
    }

    public function reset()
    {
        $this->jobs = 0;
        $this->successJobs = 0;
        $this->failedJobs = 0;

        $this->totalTime = 0;
        $this->avgTime = 0;
        $this->maxTime = 0;
        $this->minTime = 0;
    }

    public function register()
    {
    }

    public function update()
    {

    }

    public function stop()
    {

    }

    public function pause()
    {

    }

    public function resume()
    {

    }

    public function sinalHanlder()
    {

    }

    // 报告状态
    public function statusReport()
    {

    }

    public function run()
    {

    }

    public function runNextJob()
    {

    }

    public function process($job)
    {

    }
}