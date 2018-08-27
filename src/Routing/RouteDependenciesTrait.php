<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/25
 * Time: 下午9:47
 */

namespace Xdp\Routing;


trait RouteDependenciesTrait
{
    /**
     * 解析类成员方法的参数依赖
     *
     * @param $parameters
     * @param $instance
     * @param $method
     * @return mixed
     */
    public function resolveClassMethodDependencies($parameters, $instance, $method)
    {
        if (!method_exists($instance, $method)) {
            return $parameters;
        }

        return $this->resolveMethodDependencies($parameters, new \ReflectionMethod($instance, $method));
    }

    /**
     * 解析参数依赖
     *
     * @param $parameters
     * @param \ReflectionFunctionAbstract $reflector
     * @return mixed
     */
    public function resolveMethodDependencies($parameters, \ReflectionFunctionAbstract $reflector)
    {
        foreach ($reflector->getParameters() as $key => $parameter) {
            $class = $parameter->getClass();
            if (!is_null($class)) {
                throw new \UnexpectedValueException("class is not {$class->name} supported yet ");
            } else {
                if (!isset($parameters[$parameter->name]) && $parameter->isDefaultValueAvailable()) {
                    $parameters[$parameter->name] = $parameter->getDefaultValue();
                }
            }
        }

        return $parameters;
    }
}