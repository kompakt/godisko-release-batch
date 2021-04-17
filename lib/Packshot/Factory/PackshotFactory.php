<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Factory;

use Kompakt\Mediameister\Packshot\Factory\PackshotFactory as BasePackshotFactory;
use Kompakt\Mediameister\Packshot\Layout\Factory\LayoutFactoryInterface;
use Kompakt\Mediameister\Packshot\Metadata\Loader\Factory\MetadataLoaderFactoryInterface;
use Kompakt\Mediameister\Packshot\Metadata\Writer\Factory\WriterFactoryInterface as MetadataWriterFactoryInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Locator\Factory\ArtworkLocatorFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Locator\Factory\AudioLocatorFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Packshot;

class PackshotFactory extends BasePackshotFactory
{
    protected $artworkLocatorFactory = null;
    protected $audioLocatorFactory = null;

    public function __construct(
        LayoutFactoryInterface $layoutFactory,
        MetadataWriterFactoryInterface $metadataWriterFactory,
        MetadataLoaderFactoryInterface $metadataLoaderFactory,
        ArtworkLocatorFactory $artworkLocatorFactory,
        AudioLocatorFactory $audioLocatorFactory
    )
    {
        parent::__construct(
            $layoutFactory,
            $metadataWriterFactory,
            $metadataLoaderFactory
        );

        $this->artworkLocatorFactory = $artworkLocatorFactory;
        $this->audioLocatorFactory = $audioLocatorFactory;
    }

    public function getInstance($dir)
    {
        return new Packshot(
            $dir,
            $this->layoutFactory,
            $this->metadataWriterFactory,
            $this->metadataLoaderFactory,
            $this->artworkLocatorFactory,
            $this->audioLocatorFactory
        );
    }
}