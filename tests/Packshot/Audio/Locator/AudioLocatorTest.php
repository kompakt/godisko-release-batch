<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Tests\Packshot\Audio\Locator;

use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Locator\AudioLocator;
use PHPUnit\Framework\TestCase;

class AudioLocatorTest extends TestCase
{
    public function testGetAudioFile()
    {
        $locator = new AudioLocator($this->getLayout('mp3-1'), $this->getRelease());
        $this->assertNotNull($locator->getAudioFile('GBBKS1300183'));

        $locator = new AudioLocator($this->getLayout('mp3-2'), $this->getRelease());
        $this->assertNotNull($locator->getAudioFile('GBBKS1300183'));

        $locator = new AudioLocator($this->getLayout('wav-1'), $this->getRelease());
        $this->assertNotNull($locator->getAudioFile('GBBKS1300183'));

        $locator = new AudioLocator($this->getLayout('wav-2'), $this->getRelease());
        $this->assertNotNull($locator->getAudioFile('GBBKS1300183'));

        $locator = new AudioLocator($this->getLayout('aiff-1'), $this->getRelease());
        $this->assertNotNull($locator->getAudioFile('GBBKS1300183'));

        $locator = new AudioLocator($this->getLayout('aiff-2'), $this->getRelease());
        $this->assertNotNull($locator->getAudioFile('GBBKS1300183'));
    }

    protected function getLayout($subDir)
    {
        $layout = $this
            ->getMockBuilder('Kompakt\GodiskoReleaseBatch\Packshot\Layout\Layout')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $layout
            ->expects($this->any())
            ->method('getAudioDir')
            ->will($this->returnValue(sprintf('%s/_files/AudioLocatorTest/%s', __DIR__, $subDir)))
        ;

        return $layout;
    }

    protected function getRelease()
    {
        $track = $this
            ->getMockBuilder('Kompakt\GodiskoReleaseBatch\Entity\Track')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $track
            ->expects($this->any())
            ->method('getIsrc')
            ->will($this->returnValue('GBBKS1300183'))
        ;

        $release = $this
            ->getMockBuilder('Kompakt\GodiskoReleaseBatch\Entity\Release')
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