<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer;

use Kompakt\Mediameister\Packshot\Metadata\Writer\WriterInterface;
use Kompakt\GodiskoReleaseBatch\Entity\Release;
use Kompakt\GodiskoReleaseBatch\Packshot\Layout\Layout;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\Exception\InvalidArgumentException;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\XmlBuilder;

class XmlWriter implements WriterInterface
{
    protected $xmlBuilder = null;
    protected $layout = null;
    protected $release = null;

    public function __construct(
        XmlBuilder $xmlBuilder,
        Layout $layout,
        Release $release
    )
    {
        $this->xmlBuilder = $xmlBuilder;
        $this->layout = $layout;
        $this->release = $release;
    }

    public function write()
    {
        $file = $this->layout->getMetadataFile();
        $info = new \SplFileInfo(dirname($file));

        if (!$info->isDir())
        {
            throw new InvalidArgumentException(sprintf('Godisko metadata xml dir not found'));
        }

        if (!$info->isWritable())
        {
            throw new InvalidArgumentException(sprintf('Godisko metadata xml dir not writable'));
        }

        $dom = $this->xmlBuilder->build($this->release);
        $dom->formatOutput = true;
        $fileInfo = new \SplFileInfo($file);

        if ($fileInfo->isFile())
        {
            unlink($file);
        }

        $h = fopen($file, 'w');
        fwrite($h, $dom->saveXML());
        fclose($h);
    }
}