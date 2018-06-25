<?php

namespace Zend\Mvc\Di\Tests\Subjects;

class Car
{
    private $engine;

    /**
     * @return Engine
     */
    public function getEngine(): Engine
    {
        return $this->engine;
    }

    /**
     * @param Engine $engine
     */
    public function setEngine(Engine $engine): void
    {
        $this->engine = $engine;
    }

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }
}