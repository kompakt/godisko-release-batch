<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Tests\Packshot\Artwork\Locator\Factory;

use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Locator\Factory\ArtworkLocatorFactory;

class ArtworkLocatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testAll()
    {
        $factory = new ArtworkLocatorFactory($this->getLayout(), $this->getRelease());
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