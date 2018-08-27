<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/25
 * Time: 下午11:47
 */

namespace Xdp\Routing\Matching;

use Xdp\Routing\Route;
use Xdp\Http\Request;

class MethodValidator implements ValidatorInterface
{
    public function matches(Route $route, Request $request)
    {
        return in_array($request->getMethod(), $route->methods());
    }
}