<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader;

use Kompakt\GodiskoReleaseBatch\Packshot\Layout\Layout;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader\Exception\InvalidArgumentException;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader\XmlParser;
use Kompakt\Mediameister\Packshot\Metadata\Loader\MetadataLoaderInterface;

class XmlLoader implements MetadataLoaderInterface
{
    protected $xmlParser = null;
    protected $layout = null;

    public function __construct(
        XmlParser $xmlParser,
        Layout $layout
    )
    {
        $this->xmlParser = $xmlParser;
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

        $info = new \SplFileInfo($pathname);

        if (!$info->isFile())
        {
            throw new InvalidArgumentException(sprintf('Godisko metadata Xml file not found'));
        }

        if (!$info->isReadable())
        {
            throw new InvalidArgumentException(sprintf('Godisko metadata Xml file not readable'));
        }

        $handle = fopen($pathname, 'r');
        $xml = fread($handle, filesize($pathname));
        fclose($handle);

        return $this->xmlParser->parse($xml);
    }
}