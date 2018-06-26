<?php

namespace Zend\Mvc\Di\Dependency\Resolver;

use Interop\Container\ContainerInterface;
use Zend\Mvc\Di\Dependency\Injection\Injector;
use Zend\Mvc\Di\Dependency\Reflection\DependencyReflector;
use Zend\ServiceManager\ServiceManager;

/**
 * Dependency Resolver
 *
 * Create instances of the files to be resolved
 *
 * @package Zend\Mvc\Di\Dependency
 */
class Resolver
{
    /**
     * @var string
     */
    private $subject;

    /**
     * @var array
     */
    private $map = [];

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return array
     */
    public function getMap(): array
    {
        return $this->map;
    }

    /**
     * @param array $map
     */
    public function setMap(array $map): void
    {
        $this->map = $map;
    }

    /**
     * @param ServiceManager $container
     * @return mixed
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     */
    public function solve(ServiceManager &$container)
    {
        $subject = $this->getSubject();

        if($container->has($subject)) {
            $instance = $container->get($subject);
            return $instance;
        }

        $reflector = new DependencyReflector($subject);

        if(!$reflector->hasDependencies($reflector->getConstructor())) {
            $instance = $reflector->newInstance();
            $container->setService($subject, $instance);
            return $instance;
        }

        $map = $this->map[$subject];

        $instance = $reflector->newInstanceWithoutConstructor();

        $injector = new Injector();
        $instance = $injector->inject($instance, $map, $container);

        $container->setService($subject, $instance);

        return $instance;
    }
}