<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Finder;

use Kompakt\GodiskoReleaseBatch\Packshot\Layout\Layout;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Factory\XmlReaderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Finder\Exception\InvalidArgumentException;
use Kompakt\Mediameister\Packshot\Metadata\Finder\MetadataFinderInterface;

class MetadataFinder implements MetadataFinderInterface
{
    protected $metadataReaderFactory = null;
    protected $layout = null;

    public function __construct(
        XmlReaderFactory $metadataReaderFactory,
        Layout $layout
    )
    {
        $this->metadataReaderFactory = $metadataReaderFactory;
        $this->layout = $layout;
    }

    public function find()
    {
        $pathname = $this->layout->getMetadataFile();
        $fileInfo = new \SplFileInfo($pathname);

        if ($fileInfo->isFile())
        {
            return $this->metadataReaderFactory->getInstance($pathname)->read();
        }

        $otherNames = array('meta.XML');

        foreach ($otherNames as $name)
        {
            $pathname = sprintf('%s/%s', dirname($pathname), $name);
            $fileInfo = new \SplFileInfo($pathname);

            if ($fileInfo->isFile())
            {
                return $this->metadataReaderFactory->getInstance($pathname)->read();
            }
        }

        throw new InvalidArgumentException('Metadata file not found');
    }
}