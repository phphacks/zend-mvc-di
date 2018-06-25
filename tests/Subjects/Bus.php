<?php

namespace Zend\Mvc\Di\Tests\Subjects;

class Bus
{
    public function __construct(Engine $engine, $airplane)
    {
    }
}