<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader;

use Kompakt\GodiskoReleaseBatch\Packshot\Layout\Layout;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Factory\XmlReaderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader\Exception\InvalidArgumentException;
use Kompakt\Mediameister\Packshot\Metadata\Loader\MetadataLoaderInterface;

class MetadataLoader implements MetadataLoaderInterface
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

    public function getFile()
    {
        $pathname = $this->layout->getMetadataFile();
        $fileInfo = new \SplFileInfo($pathname);

        if ($fileInfo->isFile())
        {
            return $pathname;
        }

        $otherNames = array('meta.XML');

        foreach ($otherNames as $name)
        {
            $pathname = sprintf('%s/%s', dirname($pathname), $name);
            $fileInfo = new \SplFileInfo($pathname);

            if ($fileInfo->isFile())
            {
                return $pathname;
            }
        }

        return null;
    }

    public function load()
    {
        $pathname = $this->getFile();

        if ($pathname)
        {
            return $this->metadataReaderFactory->getInstance($pathname)->read();
        }

        throw new InvalidArgumentException('Metadata file not found');
    }
}