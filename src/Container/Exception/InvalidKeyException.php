<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/8/21 13341007105@163.com
 * Time: 下午6:55
 */
namespace Xdp\Container\Exception;

use Exception;
use Xdp\Contract\Container\ContainerExceptionInterface;

/**
 * Class InvalidKeyException
 * @package Xdp\Container\Exception
 */
class InvalidKeyException extends Exception implements ContainerExceptionInterface
{

}