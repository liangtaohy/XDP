<?php
/**
 * Worker For handling jobs in queue
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/12
 * Time: 下午4:37
 */
namespace Xdp\Queue;

use Exception;

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
     * QueueManager
     *
     * @var \Xdp\Queue\QueueManager
     */
    private $manager;

    /**
     * 队列名称
     *
     * @var
     */
    private $queue;

    /**
     * IoC container
     * @var \Xdp\Contract\Container\ContainerInterface
     */
    protected $app;

    /**
     * Worker constructor.
     * @param $app - IoC container
     * @param $manager - QueueManager
     * @param $queue - worker要监听的队列
     */
    public function __construct($app, $manager, $queue)
    {
        $this->app = $app;
        $this->manager = $manager;
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

    public function increJobs()
    {
        $this->jobs++;
        return $this;
    }

    public function increSuccessJobs()
    {
        $this->successJobs++;
        return $this;
    }

    public function increFailedJobs()
    {
        $this->failedJobs++;
        return $this;
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

    public function signalHandler()
    {

    }

    // 报告状态
    public function statusReport()
    {

    }

    public function run()
    {
        $q = explode(",", $this->queue);

        while(1) {
            foreach ($q as $queue) {
                try {
                    $payload = $this->manager->connection()->pop($queue);
                    if (is_null($payload)) {
                        continue;
                    }

                    if (is_array($payload) && count($payload) !== 2) {
                        continue;
                    }

                    $this->increJobs();

                    $event = Event::load($this->app, $payload[0]);

                    $status = $event->fire();

                    if ($status === 0) {
                        $this->increSuccessJobs();
                        $this->manager->connection()->delete($payload[1], $queue);
                    } else {
                        $this->increFailedJobs();
                    }
                } catch (Exception $e) {
                    echo $e->getMessage() . ":" . $e->getCode() . PHP_EOL;
                }
            }
        }
    }
}