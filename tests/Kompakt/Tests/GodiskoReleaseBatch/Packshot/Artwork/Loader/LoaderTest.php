<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\Tests\GodiskoReleaseBatch\Packshot\Artwork\Loader;

use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Loader\Loader;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetFrontArtworkFile()
    {
        $loader = new Loader($this->getLayout(), $this->getRelease());
        $this->assertNotNull($loader->getFrontArtworkFile());
    }

    protected function getLayout()
    {
        $layout = $this
            ->getMockBuilder('Kompakt\GodiskoReleaseBatch\Packshot\Layout\LayoutInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $layout
            ->expects($this->any())
            ->method('getFrontArtworkFile')
            ->will($this->returnValue(sprintf('%s/_files/LoaderTest/cover.jpg', __DIR__)))
        ;

        return $layout;
    }

    protected function getRelease()
    {
        return $this
            ->getMockBuilder('Kompakt\GodiskoReleaseBatch\Entity\ReleaseInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}