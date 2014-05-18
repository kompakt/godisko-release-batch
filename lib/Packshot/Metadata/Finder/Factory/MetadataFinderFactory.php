<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Finder\Factory;

use Kompakt\Mediameister\Packshot\Layout\LayoutInterface;
use Kompakt\Mediameister\Packshot\Metadata\Finder\Factory\MetadataFinderFactoryInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Finder\MetadataFinder;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Factory\XmlReaderFactory;

class MetadataFinderFactory implements MetadataFinderFactoryInterface
{
    protected $metadataReaderFactory = null;

    public function __construct(XmlReaderFactory $metadataReaderFactory)
    {
        $this->metadataReaderFactory = $metadataReaderFactory;
    }

    public function getInstance(LayoutInterface $layout)
    {
        return new MetadataFinder($this->metadataReaderFactory, $layout);
    }
}