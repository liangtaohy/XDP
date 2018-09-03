<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/1 13341007105@163.com
 * Time: 下午6:20
 */

namespace Xdp\Utils\Traits;


trait Singleton
{
    private static $instance;

    public static function getInstance(...$args)
    {
        if(!isset(self::$instance)){
            self::$instance = new static(...$args);
        }
        return self::$instance;
    }


}