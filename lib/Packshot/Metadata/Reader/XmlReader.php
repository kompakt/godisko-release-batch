<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader;

use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Exception\InvalidArgumentException;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\XmlParser;
use Kompakt\Mediameister\Packshot\Metadata\Reader\ReaderInterface;

class XmlReader implements ReaderInterface
{
    protected $xmlParser = null;
    protected $file = null;

    public function __construct(XmlParser $xmlParser, $file)
    {
        $info = new \SplFileInfo($file);

        if (!$info->isFile())
        {
            throw new InvalidArgumentException(sprintf('Godisko metadata Xml file not found'));
        }

        if (!$info->isReadable())
        {
            throw new InvalidArgumentException(sprintf('Godisko metadata Xml file not readable'));
        }

        $this->xmlParser = $xmlParser;
        $this->file = $file;
    }

    public function read()
    {
        $handle = fopen($this->file, 'r');
        $xml = fread($handle, filesize($this->file));
        fclose($handle);
        return $this->xmlParser->parse($xml);
    }
}