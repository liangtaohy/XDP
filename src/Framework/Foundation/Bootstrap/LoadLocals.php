<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/30
 * Time: 下午5:35
 */

namespace Xdp\Framework\Foundation\Bootstrap;

use \Xdp\Framework\Application;

class LoadLocals
{
    public function bootstrap(Application $app)
    {
        date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));
        mb_internal_encoding('UTF-8');
    }
}