<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Tests\Packshot\Artwork\Locator;

use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Locator\ArtworkLocator;

class ArtworkLocatorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetFrontArtworkFile()
    {
        $locator = new ArtworkLocator($this->getLayout(), $this->getRelease());
        $this->assertNotNull($locator->getFrontArtworkFile());
    }

    protected function getLayout()
    {
        $layout = $this
            ->getMockBuilder('Kompakt\GodiskoReleaseBatch\Packshot\Layout\Layout')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $layout
            ->expects($this->any())
            ->method('getFrontArtworkFile')
            ->will($this->returnValue(sprintf('%s/_files/ArtworkLocatorTest/cover.jpg', __DIR__)))
        ;

        return $layout;
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