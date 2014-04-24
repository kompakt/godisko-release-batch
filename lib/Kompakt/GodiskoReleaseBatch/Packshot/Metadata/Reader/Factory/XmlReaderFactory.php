<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Factory;

use Kompakt\ReleaseBatch\Entity\Release;
use Kompakt\ReleaseBatch\Entity\Track;
use Kompakt\ReleaseBatch\Packshot\Metadata\Reader\Factory\ReaderFactoryInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\XmlReader;

class XmlReaderFactory implements ReaderFactoryInterface
{
    protected $releasePrototype = null;
    protected $trackPrototype = null;

    public function __construct(Release $releasePrototype, Track $trackPrototype)
    {
        $this->releasePrototype = $releasePrototype;
        $this->trackPrototype = $trackPrototype;
    }

    public function getInstance($file)
    {
        return new XmlReader($this->releasePrototype, $this->trackPrototype, $file);
    }
}