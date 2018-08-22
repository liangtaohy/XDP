<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/8/21 13341007105@163.com
 * Time: 下午7:06
 */

namespace Xdp\Container\Exception;


use Xdp\Contract\Container\NotFoundExceptionInterface;

/**
 * Class NotFoundException
 * @package Xdp\Container\Exception
 */
class NotFoundException extends ContainerException implements NotFoundExceptionInterface
{

}