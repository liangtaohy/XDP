<?php
/**
 * The Dist Lock Should have the following features:
 * 1. mutex exclusion
 * 2. fault tolerant
 * 3. no dead lock
 * User: <Lotushy>(liangtaohy@gmail.com)
 * Date: 2019/3/29
 * Time: 6:57 PM
 */

namespace Xdp\Contract\Lock;


interface IDistLock
{
    public function lock($key, $token, $expire);
    public function unlock($key, $token);
}