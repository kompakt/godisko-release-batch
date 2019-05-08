<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer;

use Kompakt\GodiskoReleaseBatch\Entity\Release;

class XmlBuilder
{
    public function build(Release $release)
    {
        $dom = new \DOMDocument("1.0", "utf-8");
        $root = $dom->createElement('release');
        $root->appendChild($dom->createElement('label', htmlspecialchars($release->getLabel())));
        $root->appendChild($dom->createElement('name', htmlspecialchars($release->getName())));
        $root->appendChild($dom->createElement('ean', htmlspecialchars($release->getEan())));
        $root->appendChild($dom->createElement('uuid', htmlspecialchars($release->getUuid())));
        $dom->appendChild($root);
        return $dom;
    }
}