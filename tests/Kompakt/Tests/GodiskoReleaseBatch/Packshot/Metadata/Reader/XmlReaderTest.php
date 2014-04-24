<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\Tests\GodiskoReleaseBatch\Packshot\Metadata\Reader;

use Kompakt\ReleaseBatch\Entity\Artwork;
use Kompakt\ReleaseBatch\Entity\Audio;
use Kompakt\ReleaseBatch\Entity\Release;
use Kompakt\ReleaseBatch\Entity\Track;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Factory\XmlReaderFactory;

class XmlReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testValidFile()
    {
        $file = sprintf('%s/_files/XmlReaderTest/release.xml', __DIR__);
        $reader = $this->getXmlReaderFactory()->getInstance($file);
        $release = $reader->load();
        $this->assertInstanceOf('Kompakt\ReleaseBatch\Entity\Release', $release);
        $tracks = array();

        $this->assertEquals('Young Turks', $release->getLabel());

        foreach ($release->getTracks() as $track)
        {
            $tracks[$track->getTitle()] = 1;
        }

        $this->assertCount(3, $tracks);
        $this->assertArrayHasKey('Sun', $tracks);
        $this->assertArrayHasKey('On The Way', $tracks);
        $this->assertArrayHasKey('IMO', $tracks);
    }

    /**
     * @expectedException Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Exception\InvalidArgumentException
     */
    public function testFileNotFound()
    {
        $file = 'asdfasdfasdfasdf.xml';
        $reader = $this->getXmlReaderFactory()->getInstance($file);
        $reader->load();
    }

    /**
     * @expectedException Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Exception\DomainException
     */
    public function testIncompleteXml()
    {
        $file = sprintf('%s/_files/XmlReaderTest/release-incomplete.xml', __DIR__);
        $reader = $this->getXmlReaderFactory()->getInstance($file);
        $reader->load();
    }

    /**
     * @expectedException Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Exception\InvalidArgumentException
     */
    public function testInvalidXml()
    {
        $file = sprintf('%s/_files/XmlReaderTest/release-invalid.xml', __DIR__);
        $reader = $this->getXmlReaderFactory()->getInstance($file);
        $reader->load();
    }
    
    protected function getXmlReaderFactory()
    {
        return new XmlReaderFactory(new Release(new Artwork()), new Track(new Audio()));
    }
}