<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\Factory;

use Kompakt\GenericReleaseBatch\Entity\ReleaseInterface;
use Kompakt\GenericReleaseBatch\Packshot\Metadata\Writer\Factory\WriterFactoryInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\XmlWriter;

class XmlWriterFactory implements WriterFactoryInterface
{
    public function getInstance(ReleaseInterface $release)
    {
        return new XmlWriter($release);
    }
}