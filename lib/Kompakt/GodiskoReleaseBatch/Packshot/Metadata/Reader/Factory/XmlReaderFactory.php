<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Factory;

use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\XmlParser;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\XmlReader;
use Kompakt\Mediameister\Packshot\Metadata\Reader\Factory\ReaderFactoryInterface;

class XmlReaderFactory implements ReaderFactoryInterface
{
    protected $xmlParser = null;

    public function __construct(XmlParser $xmlParser)
    {
        $this->xmlParser = $xmlParser;
    }

    public function getInstance($file)
    {
        return new XmlReader($this->xmlParser, $file);
    }
}