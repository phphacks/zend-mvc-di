<?php

namespace Zend\Mvc\Di\Tests\Subjects;

class Engine
{
    private $piston;

    public function __construct(Piston $piston)
    {
        $this->piston = $piston;
    }
}