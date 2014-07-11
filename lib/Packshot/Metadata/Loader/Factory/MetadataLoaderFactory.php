<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader\Factory;

use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader\MetadataLoader;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Factory\XmlReaderFactory;
use Kompakt\Mediameister\Packshot\Layout\LayoutInterface;
use Kompakt\Mediameister\Packshot\Metadata\Loader\Factory\MetadataLoaderFactoryInterface;

class MetadataLoaderFactory implements MetadataLoaderFactoryInterface
{
    protected $metadataReaderFactory = null;

    public function __construct(XmlReaderFactory $metadataReaderFactory)
    {
        $this->metadataReaderFactory = $metadataReaderFactory;
    }

    public function getInstance(LayoutInterface $layout)
    {
        return new MetadataLoader($this->metadataReaderFactory, $layout);
    }
}