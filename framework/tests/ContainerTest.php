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

}