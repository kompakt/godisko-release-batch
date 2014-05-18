<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader\Factory;

use Kompakt\Mediameister\Packshot\Layout\LayoutInterface;
use Kompakt\Mediameister\Packshot\Metadata\Loader\Factory\LoaderFactoryInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader\Loader;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Factory\XmlReaderFactory;

class LoaderFactory implements LoaderFactoryInterface
{
    protected $metadataReaderFactory = null;

    public function __construct(XmlReaderFactory $metadataReaderFactory)
    {
        $this->metadataReaderFactory = $metadataReaderFactory;
    }

    public function getInstance(LayoutInterface $layout)
    {
        return new Loader($this->metadataReaderFactory, $layout);
    }
}