<?php

namespace Zend\Mvc\Di\Dependency\Injection;

use Zend\Mvc\Di\Dependency\Map\DependencyMapper;
use Zend\Mvc\Di\Dependency\Resolver\Resolver;
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
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     */
    public function inject($instance, array $params, ServiceManager $container)
    {
        foreach ($params as $name => $type) {
            if($container->has($name)) {
                $params[$name] = $container->get($type);
                continue;
            }

            $dependencyMapper = new DependencyMapper();
            $dependencyMapper->setSubject($type);
            $dependencyMapper->setContainer($container);
            $map = $dependencyMapper->map();

            $resolver = new Resolver();
            $resolver->setMap($map);
            $resolver->setSubject($type);

            $params[$name] = $resolver->solve($container);
        }

        $reflectionObject = new \ReflectionObject($instance);
        $reflectionObject->getMethod('__construct')->invokeArgs($instance, $params);

        return $instance;
    }
}
