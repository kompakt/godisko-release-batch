<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot;

use Kompakt\Mediameister\Packshot\Layout\Factory\LayoutFactoryInterface;
use Kompakt\Mediameister\Packshot\Metadata\Loader\Factory\MetadataLoaderFactoryInterface;
use Kompakt\Mediameister\Packshot\Metadata\Writer\Factory\WriterFactoryInterface as MetadataWriterFactoryInterface;
use Kompakt\Mediameister\Packshot\Packshot as BasePackshot;
use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Locator\Factory\ArtworkLocatorFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Locator\Factory\AudioLocatorFactory;

class Packshot extends BasePackshot
{
    protected $artworkLocatorFactory = null;
    protected $audioLocatorFactory = null;
    protected $artworkLocator = null;
    protected $audioLocator = null;

    public function __construct(
        $dir,
        LayoutFactoryInterface $layoutFactory,
        MetadataWriterFactoryInterface $metadataWriterFactory,
        MetadataLoaderFactoryInterface $metadataLoaderFactory,
        ArtworkLocatorFactory $artworkLocatorFactory,
        AudioLocatorFactory $audioLocatorFactory
    )
    {
        parent::__construct(
            $dir,
            $layoutFactory,
            $metadataWriterFactory,
            $metadataLoaderFactory
        );

        $this->artworkLocatorFactory = $artworkLocatorFactory;
        $this->audioLocatorFactory = $audioLocatorFactory;
    }

    public function getArtworkLocator()
    {
        return $this->artworkLocator;
    }

    public function getAudioLocator()
    {
        return $this->audioLocator;
    }

    public function load()
    {
        parent::load();

        $this->artworkLocator = $this->artworkLocatorFactory->getInstance($this->layout, $this->release);
        $this->audioLocator = $this->audioLocatorFactory->getInstance($this->layout, $this->release);

        return $this;
    }
}