<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/25
 * Time: 下午11:41
 */

namespace Xdp\Routing\Matching;

use Xdp\Routing\Route;
use Xdp\Http\Request;

class UrlValidator implements ValidatorInterface
{
    public function matches(Route $route, Request $request)
    {
        return preg_match($route->compiledRoute()->getRegex(), rawurldecode($request->path()));
    }
}