<?php

namespace Zend\Mvc\Di\Tests\Dependency\Reflection;

use PHPUnit\Framework\TestCase;
use Zend\Mvc\Di\Dependency\Reflection\DependencyReflector;
use Zend\Mvc\Di\Tests\Subjects\Bus;
use Zend\Mvc\Di\Tests\Subjects\Car;
use Zend\Mvc\Di\Tests\Subjects\Engine;
use Zend\Mvc\Di\Tests\Subjects\Piston;
use Zend\ServiceManager\ServiceManager;

/**
 * DependencyReflectorTest
 *
 * @package Zend\Mvc\Di\Tests\Dependency\Reflection
 */
class DependencyReflectorTest extends TestCase
{
    /**
     * When a solvable dependency is declared it should be mapped.
     *
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     */
    public function testIfDependenciesAreDetected()
    {
        $reflector = new DependencyReflector(Car::class);
        $reflector->setContainer(new ServiceManager());
        $dependencies = $reflector->getDependencies();

        $this->assertEquals(1, count($dependencies));
        $this->assertContains(Engine::class, $dependencies);
        $this->assertArrayHasKey('engine', $dependencies);
    }

    /**
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     */
    public function testIfSubDependenciesAreDetected()
    {
        $carReflector = new DependencyReflector(Car::class);
        $carReflector->setContainer(new ServiceManager());
        $carReflector->getDependencies();

        $engineReflector = new DependencyReflector(Engine::class);
        $engineReflector->setContainer(new ServiceManager());
        $engineReflector->getDependencies();

        $this->assertTrue($carReflector->hasSubDependencies());
        $this->assertFalse($engineReflector->hasSubDependencies());
    }

    /**
     * When a dependency is not solvable a exception should be thrown.
     *
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     * @expectedException \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     */
    public function testIfUnsolvableDependenciesThrowsException()
    {
        $reflector = new DependencyReflector(Bus::class);
        $reflector->setContainer(new ServiceManager());
        $reflector->getDependencies();
    }
}