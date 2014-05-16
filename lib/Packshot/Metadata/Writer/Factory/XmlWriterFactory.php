<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\Factory;

use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\XmlWriter;
use Kompakt\Mediameister\Packshot\Metadata\Writer\Factory\WriterFactoryInterface;

class XmlWriterFactory implements WriterFactoryInterface
{
    public function getInstance()
    {
        return new XmlWriter();
    }
}