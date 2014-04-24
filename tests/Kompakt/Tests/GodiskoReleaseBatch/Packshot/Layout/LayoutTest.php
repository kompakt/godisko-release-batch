<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\Tests\GodiskoReleaseBatch\Packshot\Layout;

use Kompakt\GodiskoReleaseBatch\Packshot\Layout\Layout;

class LayoutTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $layout = new Layout(__DIR__);
        $this->assertRegExp('/meta.xml/', $layout->getMetadataFile());
        $this->assertRegExp('/cover.jpg/', $layout->getFrontArtworkFile());
    }
}