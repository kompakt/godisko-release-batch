<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Tests\Packshot\Audio\Locator\Factory;

use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Locator\Factory\AudioLocatorFactory;
use PHPUnit\Framework\TestCase;

class AudioLocatorFactoryTest extends TestCase
{
    public function testAll()
    {
        $factory = new AudioLocatorFactory($this->getLayout(), $this->getRelease());

        $this->assertTrue(true);
    }

    protected function getLayout()
    {
        return $this
            ->getMockBuilder('Kompakt\GodiskoReleaseBatch\Packshot\Layout\Layout')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    protected function getRelease()
    {
        return $this
            ->getMockBuilder('Kompakt\GodiskoReleaseBatch\Entity\Release')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}