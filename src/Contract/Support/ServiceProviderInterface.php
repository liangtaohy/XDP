<?php
/**
 *
 * User: <Lotushy>(liangtaohy@gmail.com)
 * Date: 2018/11/2
 * Time: 10:14 AM
 */

namespace Xdp\Contract\Support;

/**
 * Interface ServiceProviderInterface
 * @package Xdp\Contract\Support
 */
interface ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register();
}