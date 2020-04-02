<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Tests\Packshot\Layout;

use Kompakt\GodiskoReleaseBatch\Packshot\Layout\Layout;
use PHPUnit\Framework\TestCase;

class LayoutTest extends TestCase
{
    public function testGetters()
    {
        $layout = new Layout(__DIR__);
        $this->assertRegExp('/meta.xml/', $layout->getMetadataFile());
        $this->assertRegExp('/cover.jpg/', $layout->getFrontArtworkFile());
    }
}