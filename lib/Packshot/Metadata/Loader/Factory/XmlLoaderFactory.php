<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader\Factory;

use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader\XmlLoader;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader\XmlParser;
use Kompakt\Mediameister\Packshot\Layout\LayoutInterface;
use Kompakt\Mediameister\Packshot\Metadata\Loader\Factory\MetadataLoaderFactoryInterface;

class XmlLoaderFactory implements MetadataLoaderFactoryInterface
{
    protected $xmlParser = null;

    public function __construct(XmlParser $xmlParser)
    {
        $this->xmlParser = $xmlParser;
    }

    public function getInstance(LayoutInterface $layout)
    {
        return new XmlLoader($this->xmlParser, $layout);
    }
}