<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/8/21 13341007105@163.com
 * Time: 下午6:58
 */

namespace Xdp\Container\Exception;


use Xdp\Contract\Container\NotFoundExceptionInterface;

/**
 * Class KeyExistsException
 * @package Xdp\Container\Exception
 */
class KeyExistsException extends ContainerException implements NotFoundExceptionInterface
{

}