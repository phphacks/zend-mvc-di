<?php

namespace Zend\Mvc\Di\Tests\Dependency\Map;

use PHPUnit\Framework\TestCase;
use Zend\Mvc\Di\Dependency\Map\DependencyMapper;
use Zend\Mvc\Di\Tests\Subjects\Bus;
use Zend\Mvc\Di\Tests\Subjects\Car;
use Zend\Mvc\Di\Tests\Subjects\Piston;
use Zend\ServiceManager\ServiceManager;

/**
 * DependencyMapperTest
 *
 * @package Zend\Mvc\Di\Tests\Dependency\Map
 */
class DependencyMapperTest extends TestCase
{
    /**
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     */
    public function testMapping()
    {
        $mapper = new DependencyMapper();
        $mapper->setSubject(Car::class);
        $mapper->setContainer(new ServiceManager());
        $map = $mapper->map();

        $this->assertCount(2, $map);
    }

    /**
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     */
    public function testFlatMapping()
    {
        $mapper = new DependencyMapper();
        $mapper->setSubject(Car::class);
        $mapper->setContainer(new ServiceManager());
        $mapper->map();
        $map = $mapper->getFlatMap();

        $this->assertCount(3, $map);
        $this->assertEquals($map[0], Piston::class);
    }

    /**
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     * @expectedException \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     */
    public function testMappingClassWithUnsolvableDependencies()
    {
        $mapper = new DependencyMapper();
        $mapper->setSubject(Bus::class);
        $mapper->setContainer(new ServiceManager());
        $mapper->map();
    }
}