<?php

namespace Jahir\Framework\Tests;

use Jahir\Framework\Container\Container;
use Jahir\Framework\Container\ContainerException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    /** @test */
    public function a_service_can_be_retrieved_from_the_container() {

        $container = new Container();

        $container->add('dependant-class', DependantClass::class);

        $this->assertInstanceOf(DependantClass::class, $container->get('dependant-class'));
    }

    /** @test */
    public function a_container_exception_will_be_thrown_if_service_not_found() {

        $container = new Container();

        $this->expectException(ContainerException::class);

        $container->add('foobar');
    }

    /** @test */
    public function can_check_if_the_container_has_a_service() {

        $container = new Container();

        $container->add('dependant-class', DependantClass::class);

        $this->assertTrue($container->has('dependant-class'));

        $this->assertFalse($container->has('non-existing-class'));
    }


    /** @test */
    public function services_can_be_recursively_autowired() {
        $container = new Container();

        $container->add('dependant-class', DependantClass::class);

        /** @var DependantClass $dependantClass */
        $dependantClass = $container->get('dependant-class');

        $this->assertInstanceOf(DependencyClass::class, $dependantClass->getDependencyClass());
        $this->assertInstanceOf(SubDependencyClass::class, $dependantClass->getDependencyClass()->getSubDependencyClass());
    }

}