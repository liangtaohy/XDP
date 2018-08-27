<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/25
 * Time: 下午11:48
 */

namespace Xdp\Routing\Matching;

use Xdp\Routing\Route;
use Xdp\Http\Request;

class SchemeValidator implements ValidatorInterface
{
    public function matches(Route $route, Request $request)
    {
        if ($route->httpOnly()) {
            return ! $request->secure();
        } else if ($route->httpsOnly()) {
            return $request->secure();
        }

        return true;
    }
}