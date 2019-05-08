<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\Factory;

use Kompakt\Mediameister\Entity\ReleaseInterface;
use Kompakt\Mediameister\Packshot\Layout\LayoutInterface;
use Kompakt\Mediameister\Packshot\Metadata\Writer\Factory\WriterFactoryInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\XmlBuilder;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\XmlWriter;

class XmlWriterFactory implements WriterFactoryInterface
{
    public function __construct(XmlBuilder $xmlBuilder)
    {
        $this->xmlBuilder = $xmlBuilder;
    }

    public function getInstance(LayoutInterface $layout, ReleaseInterface $release)
    {
        return new XmlWriter(
            $this->xmlBuilder,
            $layout,
            $release
        );
    }
}