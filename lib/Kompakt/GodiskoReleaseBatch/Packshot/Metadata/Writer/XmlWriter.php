<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer;

use Kompakt\GodiskoReleaseBatch\Entity\ReleaseInterface;
use Kompakt\Mediameister\Packshot\Metadata\Writer\WriterInterface;

class XmlWriter implements WriterInterface
{
    public function save(ReleaseInterface $release, $file)
    {
        // TODO
    }
}