<?php

namespace Xplore\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Xplore\Classes\Container;
use Xplore\Exceptions\ContainerException;

class ContainerTest extends TestCase
{
    #[Test]
    public function a_service_can_be_retrieved_from_the_container()
    {
        $container = new Container();

        $container->add('dependant-class', DependantClass::class);

        $this->assertInstanceOf(DependantClass::class, $container->get('dependant-class'));
    }

    #[Test]
    public function a_ContainerException_is_thrown_if_a_service_cannot_be_found()
    {
        // Setup
        $container = new Container();

        // Expect exception
        $this->expectException(ContainerException::class);

        // Do something
        $container->add('foobar');
    }
}