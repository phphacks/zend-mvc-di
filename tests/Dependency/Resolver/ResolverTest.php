<?php

namespace Zend\Mvc\Di\Tests\Dependency\Resolver;

use PHPUnit\Framework\TestCase;
use Zend\Mvc\Di\Dependency\Map\DependencyMapper;
use Zend\Mvc\Di\Dependency\Resolver\Resolver;
use Zend\Mvc\Di\Tests\Subjects\Car;
use Zend\ServiceManager\ServiceManager;

class ResolverTest extends TestCase
{
    /**
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     */
    public function testInjectionForAItemThatAlreadyExistsInAContainer()
    {
        $serviceManager = new ServiceManager();

        $resolver = new Resolver();

        $dependencyMapper = new DependencyMapper();
        $dependencyMapper->setSubject(Car::class);

        $map = $dependencyMapper->map();
        $resolver->setMap($map);

        $flatMap = $dependencyMapper->getFlatMap();

        $car = null;

        foreach ($flatMap as $item) {
            $resolver->setSubject($item);
            $resolver->solve($serviceManager);
        }

        $car = $serviceManager->get(Car::class);
        $this->assertInstanceOf(Car::class, $car);
    }
}