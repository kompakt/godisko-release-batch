<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Tests\Packshot\Metadata\Reader;

use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Factory\XmlReaderFactory;

class XmlReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testValidFile()
    {
        $file = sprintf('%s/_files/XmlReaderTest/release.xml', __DIR__);
        $reader = $this->getXmlReaderFactory()->getInstance($file);
        $release = $reader->load();
        $this->assertInstanceOf('Kompakt\GodiskoReleaseBatch\Entity\Release', $release);
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
    
    protected function getXmlReaderFactory()
    {
        $release = $this
            ->getMockBuilder('Kompakt\GodiskoReleaseBatch\Entity\Release')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $parser = $this
            ->getMockBuilder('Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\XmlParser')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $parser
            ->expects($this->any())
            ->method('parse')
            ->will($this->returnValue($release))
        ;

        return new XmlReaderFactory($parser);
    }
}