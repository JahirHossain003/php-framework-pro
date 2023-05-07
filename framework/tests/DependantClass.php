<?php

namespace Jahir\Framework\Tests;

class DependantClass
{
    public function __construct(private DependencyClass $dependencyClass)
    {
    }

    /**
     * @return DependencyClass
     */
    public function getDependencyClass(): DependencyClass
    {
        return $this->dependencyClass;
    }

}