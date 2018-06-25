<?php

namespace Zend\Mvc\Di\Dependency\Injection;

use Interop\Container\ContainerInterface;
use Zend\Mvc\Di\Dependency\Map\DependencyMapper;
use Zend\Mvc\Di\Dependency\Resolver\Resolver;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * ActionControllerFactory
 *
 * Creates a controller and inject all of it dependencies
 * into it in runtime.
 *
 * @package Zend\Mvc\Di\Controller\Factory
 */
class InjectableFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $resolver = new Resolver();

        $dependencyMapper = new DependencyMapper();
        $dependencyMapper->setSubject($requestedName);

        $map = $dependencyMapper->map();
        $resolver->setMap($map);

        $flatMap = $dependencyMapper->getFlatMap();

        $car = null;

        foreach ($flatMap as $item) {
            $resolver->setSubject($item);
            $resolver->solve($container);
        }

        return $container->get($requestedName);
    }
}