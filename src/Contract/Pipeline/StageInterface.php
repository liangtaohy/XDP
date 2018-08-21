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
interface StageInterface
{
    /**
     * @link http://php.net/manual/zh/language.oop5.magic.php#object.invoke
     * @param $payload
     * @return mixed
     */
    public function __invoke($payload);
}