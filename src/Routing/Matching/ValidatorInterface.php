<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/25
 * Time: 下午11:53
 */

namespace Xdp\Routing\Matching;

use Xdp\Http\Request;
use Xdp\Routing\Route;

interface ValidatorInterface
{
    /**
     * Validate a given rule against a route and request.
     *
     * @param  \Xdp\Routing\Route  $route
     * @param  \Xdp\Http\Request  $request
     * @return bool
     */
    public function matches(Route $route, Request $request);
}