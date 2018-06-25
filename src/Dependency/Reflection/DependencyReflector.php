<?php

namespace Zend\Mvc\Di\Dependency\Reflection;
use Zend\Mvc\Di\Exceptions\UnsolvableDependencyException;

/**
 * DependencyReflection
 *
 * @package Zend\Mvc\Di\Dependency\Reflection
 */
class DependencyReflector extends \ReflectionClass
{
    /**
     * @var bool
     */
    private $hasSubDependencies = false;

    /**
     * @return bool
     */
    public function hasSubDependencies(): bool
    {
        return $this->hasSubDependencies;
    }

    /**
     * isSolvable
     *
     * Verifica se o parâmetro informado pode ser resolvido e
     * injetado automaticamente.
     *
     * @param \ReflectionParameter $parameter
     * @return bool
     * @throws \ReflectionException
     */
    private function isSolvable(\ReflectionParameter $parameter): bool
    {
        if ($parameter->getType() == null && !$parameter->isOptional()) {
            return false;
        }

        $reflection = new \ReflectionClass($parameter->getType()->getName());
        if (!$reflection->isInstantiable()) {
            return false;
        }

        return true;
    }

    /**
     * @param null|\ReflectionMethod $constructor
     * @return bool
     * @throws UnsolvableDependencyException
     * @throws \ReflectionException
     */
    public function hasDependencies($constructor): bool
    {
        if (empty($constructor) || $constructor->getNumberOfParameters() == 0) {
            return false;
        }

        foreach ($constructor->getParameters() as $parameter) {
            if (!$this->isSolvable($parameter)) {
                throw new UnsolvableDependencyException(sprintf('Is not possible to solve parameter "$%s" in class "%s"',
                    $parameter->getName(),
                    $constructor->getDeclaringClass()->getName()
                ));
            }

            $dependency = $parameter->getType()->getName();
            $reflector = new DependencyReflector($dependency);
            $this->hasSubDependencies = $reflector->hasDependencies($reflector->getConstructor());
        }

        return true;
    }

    /**
     * @return array
     * @throws UnsolvableDependencyException
     * @throws \ReflectionException
     */
    public function getDependencies(): array
    {
        $dependencies = [];
        $constructor = $this->getConstructor();

        if (!$this->hasDependencies($constructor)) {
            return $dependencies;
        }

        foreach ($constructor->getParameters() as $parameter) {
            $name = $parameter->getName();
            $type = $parameter->getType()->getName();
            $dependencies[$name] = $type;
        }

        return $dependencies;
    }
}