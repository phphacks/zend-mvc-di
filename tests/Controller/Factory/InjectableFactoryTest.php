<?php

namespace Zend\Mvc\Di\Tests\Controller;

use Zend\Mvc\Di\Dependency\Injection\InjectableFactory;
use PHPUnit\Framework\TestCase;
use Zend\Mvc\Di\Tests\Subjects\Car;
use Zend\ServiceManager\ServiceManager;

class InjectableFactoryTest extends TestCase
{
    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     */
    public function testIfDependenciesAreSolvedWithInjectableFactory()
    {
        $serviceManager = new ServiceManager();
        $injectableFactory = new InjectableFactory();
        $car = $injectableFactory->__invoke($serviceManager, Car::class);

        $this->assertInstanceOf(Car::class, $car);
    }
}