<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\Tests\GodiskoReleaseBatch\Packshot\Audio\Loader;

use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Loader\Loader;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAudioFile()
    {
        $loader = new Loader($this->getLayout('mp3-1'), $this->getRelease());
        $this->assertNotNull($loader->getAudioFile('GBBKS1300183'));

        $loader = new Loader($this->getLayout('mp3-2'), $this->getRelease());
        $this->assertNotNull($loader->getAudioFile('GBBKS1300183'));

        $loader = new Loader($this->getLayout('wav-1'), $this->getRelease());
        $this->assertNotNull($loader->getAudioFile('GBBKS1300183'));

        $loader = new Loader($this->getLayout('wav-2'), $this->getRelease());
        $this->assertNotNull($loader->getAudioFile('GBBKS1300183'));

        $loader = new Loader($this->getLayout('aiff-1'), $this->getRelease());
        $this->assertNotNull($loader->getAudioFile('GBBKS1300183'));

        $loader = new Loader($this->getLayout('aiff-2'), $this->getRelease());
        $this->assertNotNull($loader->getAudioFile('GBBKS1300183'));
    }

    protected function getLayout($subDir)
    {
        $layout = $this
            ->getMockBuilder('Kompakt\GodiskoReleaseBatch\Packshot\Layout\LayoutInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $layout
            ->expects($this->any())
            ->method('getAudioDir')
            ->will($this->returnValue(sprintf('%s/_files/LoaderTest/%s', __DIR__, $subDir)))
        ;

        return $layout;
    }

    protected function getRelease()
    {
        $track = $this
            ->getMockBuilder('Kompakt\ReleaseBatch\Entity\Track')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $track
            ->expects($this->any())
            ->method('getIsrc')
            ->will($this->returnValue('GBBKS1300183'))
        ;

        $release = $this
            ->getMockBuilder('Kompakt\ReleaseBatch\Entity\Release')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $release
            ->expects($this->any())
            ->method('getTracks')
            ->will($this->returnValue(array($track)))
        ;

        return $release;
    }
}