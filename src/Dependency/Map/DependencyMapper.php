<?php

namespace Zend\Mvc\Di\Dependency\Map;

use Zend\Mvc\Di\Dependency\Reflection\DependencyReflector;

/**
 * DependencyMapFactory
 *
 * @package Zend\Mvc\Di\Dependency\Map
 */
class DependencyMapper
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
     * @var array
     */
    private $flatMap = [];

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
    public function getFlatMap(): array
    {
        return array_reverse($this->flatMap);
    }

    /**
     * @return array
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     */
    public function map(): array
    {
        $reflector = new DependencyReflector($this->getSubject());
        $dependencies = $reflector->getDependencies();
        $this->map[$this->getSubject()] = $dependencies;

        $this->flatMap[] = $this->getSubject();
        $this->flatMap = array_merge($this->flatMap, array_values($dependencies));
        $this->flatMap = array_unique($this->flatMap);

        if($reflector->hasSubDependencies()){
            foreach ($dependencies as $dependency){
                $this->setSubject($dependency);
                $this->map();
            }
        }

        return $this->map;
    }
}