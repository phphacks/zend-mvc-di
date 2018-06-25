<?php

namespace Zend\Mvc\Di\Dependency\Injection;

use Zend\ServiceManager\ServiceManager;

/**
 * Injector
 *
 * @package Zend\Mvc\Di\Tests\Dependency
 */
class Injector
{
    /**
     * @param $instance
     * @param array $params
     * @param ServiceManager $container
     * @return mixed
     */
    public function inject($instance, array $params, ServiceManager $container)
    {
        foreach ($params as $name => $type) {
            $params[$name] = $container->get($type);
        }

        $reflectionObject = new \ReflectionObject($instance);
        $reflectionObject->getMethod('__construct')->invokeArgs($instance, $params);

        return $instance;
    }
}