<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/25
 * Time: 下午11:50
 */

namespace Xdp\Routing\Matching;

use Xdp\Routing\Route;
use Xdp\Http\Request;

class HostValidator implements ValidatorInterface
{
    public function matches(Route $route, Request $request)
    {
        if (is_null($route->compiledRoute()->getHostRegex())) {
            return true;
        }

        return preg_match($route->compiledRoute()->getHostRegex(), $request->getHost());
    }
}