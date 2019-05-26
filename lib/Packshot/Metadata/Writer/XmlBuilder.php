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
        $root->appendChild($dom->createElement('data_version', 2));
        $root->appendChild($dom->createElement('labelname', htmlspecialchars($release->getLabel())));
        $root->appendChild($dom->createElement('release_artist', htmlspecialchars($release->getArtist())));
        $root->appendChild($dom->createElement('release_name', htmlspecialchars($release->getName())));
        $root->appendChild($dom->createElement('release_ean', htmlspecialchars($release->getEan())));
        $root->appendChild($dom->createElement('release_catno', htmlspecialchars($release->getCatalogNumber())));
        $root->appendChild($dom->createElement('release_uuid', htmlspecialchars($release->getUuid())));

        $date = $release->getPhysicalReleaseDate();

        if ($date instanceof \DateTime)
        {
            $root->appendChild($dom->createElement('release_physical_releasedate', htmlspecialchars($date->format('Ymd'))));
        }

        $date = $release->getDigitalReleaseDate();

        if ($date instanceof \DateTime)
        {
            $root->appendChild($dom->createElement('release_digital_releasedate', htmlspecialchars($date->format('Ymd'))));
        }

        $root->appendChild($dom->createElement('release_originalformat', htmlspecialchars($release->getOriginalFormat())));
        $root->appendChild($dom->createElement('release_short_info_e', htmlspecialchars($release->getShortInfoEn())));
        $root->appendChild($dom->createElement('release_short_info_d', htmlspecialchars($release->getShortInfoDe())));
        $root->appendChild($dom->createElement('release_info_e', htmlspecialchars($release->getInfoEn())));
        $root->appendChild($dom->createElement('release_info_d', htmlspecialchars($release->getInfoDe())));
        $root->appendChild($dom->createElement('release_saleterritories', htmlspecialchars($release->getSaleTerritories())));
        $root->appendChild($dom->createElement('release_bundlerestriction', htmlspecialchars($release->getBundleRestriction())));

        $tracks = $dom->createElement('tracks');
        $root->appendChild($tracks);

        foreach ($release->getTracks() as $t)
        {
            $track = $dom->createElement('track');
            $track->appendChild($dom->createElement('track_isrc', htmlspecialchars($t->getIsrc())));
            $track->appendChild($dom->createElement('track_uuid', htmlspecialchars($t->getUuid())));
            $track->appendChild($dom->createElement('track_originalposition', htmlspecialchars($t->getPosition())));
            $track->appendChild($dom->createElement('track_artist', htmlspecialchars($t->getArtist())));
            $track->appendChild($dom->createElement('track_composer', htmlspecialchars($t->getComposer())));
            $track->appendChild($dom->createElement('track_songwriter', htmlspecialchars($t->getSongwriter())));
            $track->appendChild($dom->createElement('track_publisher', htmlspecialchars($t->getPublisher())));
            $track->appendChild($dom->createElement('track_title', htmlspecialchars($t->getTitle())));
            $track->appendChild($dom->createElement('track_version', htmlspecialchars($t->getVersion())));
            $track->appendChild($dom->createElement('track_genre', htmlspecialchars($t->getGenre())));
            $track->appendChild($dom->createElement('track_media', htmlspecialchars($t->getMedia())));
            $track->appendChild($dom->createElement('track_num_disc_num', htmlspecialchars($t->getDiscNr())));
            $track->appendChild($dom->createElement('track_num_album_num', htmlspecialchars($t->getAlbumNr())));
            $track->appendChild($dom->createElement('track_duration', htmlspecialchars($t->getDuration())));
            $tracks->appendChild($track);
        }

        $dom->appendChild($root);
        return $dom;
    }
}