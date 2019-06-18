<?php
/**
 * Created by PhpStorm.
 * User: <Lotushy>(liangtaohy@gmail.com)
 * Date: 2019/3/29
 * Time: 6:59 PM
 */

namespace Xdp\Contract\Lock;


interface IDistLockFactory
{
    /**
     * generator lock instance
     *
     * @return \Xdp\Contract\Lock\IDistLock
     */
    public function locker();
}