<?php

namespace Zend\Mvc\Di\Tests\Dependency\Injection;

use PHPUnit\Framework\TestCase;
use Zend\Mvc\Di\Dependency\Injection\Injector;
use Zend\Mvc\Di\Tests\Subjects\Car;
use Zend\Mvc\Di\Tests\Subjects\Engine;
use Zend\Mvc\Di\Tests\Subjects\Piston;
use Zend\ServiceManager\ServiceManager;

class InjectorTest extends TestCase
{
    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function testInjectionForAItemThatAlreadyExistsInAContainer()
    {
        $serviceManager = new ServiceManager();
        $serviceManager->setFactory(Engine::class, function() {
            $piston = new Piston();
            return new Engine($piston);
        });

        $reflection = new \ReflectionClass(Car::class);
        $instance = $reflection->newInstanceWithoutConstructor();

        $dependencies = ['engine' => Engine::class];

        $injector = new Injector();
        $instance = $injector->inject($instance, $dependencies, $serviceManager);

        $this->assertInstanceOf(Car::class, $instance);
        $this->assertInstanceOf(Engine::class, $instance->getEngine());
    }
}