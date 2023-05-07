<?php

namespace Jahir\Framework\Tests;

class DependencyClass
{
    public function __construct(private SubDependencyClass $subDependencyClass)
    {
    }

    /**
     * @return SubDependencyClass
     */
    public function getSubDependencyClass(): SubDependencyClass
    {
        return $this->subDependencyClass;
    }

}