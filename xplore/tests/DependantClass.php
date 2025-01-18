<?php

namespace Xplore\Tests;

class DependantClass
{
    public function __construct(private SubDependencyClass $subDependency)
    {
    }

    public function getSubDependency(): SubDependencyClass
    {
        return $this->subDependency;
    }
}