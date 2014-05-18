<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Tests\Packshot\Artwork\Finder;

use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Finder\ArtworkFinder;

class ArtworkFinderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetFrontArtworkFile()
    {
        $finder = new ArtworkFinder($this->getLayout(), $this->getRelease());
        $this->assertNotNull($finder->getFrontArtworkFile());
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
            ->will($this->returnValue(sprintf('%s/_files/ArtworkFinderTest/cover.jpg', __DIR__)))
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