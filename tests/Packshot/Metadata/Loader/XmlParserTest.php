<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Tests\Packshot\Metadata\Loader;

use Kompakt\GodiskoReleaseBatch\Entity\Release;
use Kompakt\GodiskoReleaseBatch\Entity\Track;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader\Exception\DomainException;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader\Exception\InvalidArgumentException;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader\XmlParser;
use PHPUnit\Framework\TestCase;

class XmlParserTest extends TestCase
{
    public function testValidFile()
    {
        $file = sprintf('%s/_files/XmlParserTest/release.xml', __DIR__);
        $xmlParser = new XmlParser(new Release(), new Track());
        $release = $xmlParser->parse($this->getFileContents($file));

        $this->assertInstanceOf('Kompakt\GodiskoReleaseBatch\Entity\Release', $release);
    }

    public function testInvalidFile()
    {
        $this->expectException(InvalidArgumentException::class);

        $file = sprintf('%s/_files/XmlParserTest/release-invalid.xml', __DIR__);
        $xmlParser = new XmlParser(new Release(), new Track());
        $release = $xmlParser->parse($this->getFileContents($file));
    }

    public function testIncompleteFile()
    {
        $this->expectException(DomainException::class);

        $file = sprintf('%s/_files/XmlParserTest/release-incomplete.xml', __DIR__);
        $xmlParser = new XmlParser(new Release(), new Track());
        $release = $xmlParser->parse($this->getFileContents($file));
    }

    protected function getFileContents($file)
    {
        $handle = fopen($file, 'r');
        $data = fread($handle, filesize($file));
        fclose($handle);

        return $data;
    }
}