<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader;

use Kompakt\GodiskoReleaseBatch\Entity\Release;
use Kompakt\GodiskoReleaseBatch\Entity\Track;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Exception\DomainException;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Exception\InvalidArgumentException;

class XmlParser
{
    protected $releasePrototype = null;
    protected $trackPrototype = null;

    public function __construct(Release $releasePrototype, Track $trackPrototype)
    {
        $this->releasePrototype = $releasePrototype;
        $this->trackPrototype = $trackPrototype;
    }

    public function parse($xml)
    {
        return $this->doParse($this->fixRawXml($xml));
    }

    protected function fixRawXml($xml)
    {
        $patterns = array(
            '/\\x00/' => '', // echo bin2hex('Ê');
            '/\\x01/' => '',
            '/\\x02/' => '',
            '/\\x03/' => '',
            '/\\x13/' => '', // 
            '/\\x18/' => '', // 
            '/\\x19/' => '',
            '/\\x1c/' => '',
            '/\\x1d/' => '',
            '/\\x1e/' => '', // 
            '/\\x1e/' => '',
            '/Ê/' => ' ',
            '/ /' => ' ',
            '/&#8233;/' => ' ', # 0x2029 - linefeed - '/ /' (cause of hotmail problem)
            '/†/' => '' // &#x2020; &dagger;
        );

        $toUtf = function($matches)
        {
            if (isset($matches[1]) && isset($matches[2]))
            {
                $encoding = mb_detect_encoding($matches[2], array('UTF-8', 'ISO-8859-1'));
                $val = mb_convert_encoding($matches[2], 'UTF-8', $encoding);
                $val = html_entity_decode($val, ENT_COMPAT, 'UTF-8');
                $val = htmlspecialchars($val, ENT_COMPAT, 'UTF-8');
                return '<'.$matches[1].'>'.$val.'</'.$matches[1].'>';
            }

            return '';
        };

        foreach ($patterns as $find => $replace)
        {
            $xml = preg_replace($find, $replace, $xml);
        }

        $xml = preg_replace('/\&apos;/i', "'", $xml);
        $xml = preg_replace_callback('/<(\w*)>([^<>]*)<\/\w*>/', $toUtf, $xml);
        return preg_replace('/ISO-8859-1/i', 'utf-8', $xml);
    }

    protected function doParse($xml)
    {
        set_error_handler(function($errno, $errstr, $errfile = null, $errline = null, array $errcontext = null)
        {
            throw new InvalidArgumentException($errstr);
        });

        $dom = new \DOMDocument();
        $dom->loadXml($xml);
        restore_error_handler();

        $fixField = function($s)
        {
            return preg_replace('/\s+/', " ", trim($s));
        };

        $fixReleaseDate = function($releaseDate)
        {
            if (preg_match('/(\d{4,4})(\d{2,2})(\d{2,2})/', $releaseDate, $matches))
            {
                $date = sprintf('%d-%d-%d', $matches[1], $matches[2], $matches[3]);
                return \DateTime::createFromFormat('Y-m-d', $date);
            }

            return '0000-00-00';
        };

        $fixBundleRestriction = function($bundleRestriction)
        {
            return (preg_match('/^True$/i', $bundleRestriction)) ? 1 : 0;
        };

        $release = clone $this->releasePrototype;
        $release->setLabel($fixField($this->getDomVal($dom, 'labelname')));
        $release->setName($fixField($this->getDomVal($dom, 'release_name')));
        $release->setEan($fixField($this->getDomVal($dom, 'release_ean')));
        $release->setCatalogNumber($fixField($this->getDomVal($dom, 'release_catno')));
        $release->setPhysicalReleaseDate($fixReleaseDate($this->getDomVal($dom, 'release_physical_releasedate')));
        $release->setDigitalReleaseDate($fixReleaseDate($this->getDomVal($dom, 'release_digital_releasedate')));
        $release->setOriginalFormat($fixField($this->getDomVal($dom, 'release_originalformat')));
        $release->setShortInfoEn($fixField($this->getDomVal($dom, 'release_short_info_e')));
        $release->setShortInfoDe($fixField($this->getDomVal($dom, 'release_short_info_d')));
        $release->setInfoEn($fixField($this->getDomVal($dom, 'release_info_e')));
        $release->setInfoDe($fixField($this->getDomVal($dom, 'release_info_d')));
        $release->setSaleTerritories($fixField($this->getDomVal($dom, 'release_saleterritories')));
        $release->setBundleRestriction($fixBundleRestriction($this->getDomVal($dom, 'release_bundlerestriction')));

        $tracks = $this->getDomElement($dom, 'tracks');

        foreach ($tracks->getElementsByTagName('track') as $t)
        {
            $track = clone $this->trackPrototype;
            $track->setIsrc($fixField($this->getDomVal($t, 'track_isrc')));
            $track->setPosition($fixField($this->getDomVal($t, 'track_originalposition')));
            $track->setArtist($fixField($this->getDomVal($t, 'track_artist')));
            $track->setComposer($fixField($this->getDomVal($t, 'track_composer')));
            $track->setSongwriter($fixField($this->getDomVal($t, 'track_songwriter')));
            $track->setPublisher($fixField($this->getDomVal($t, 'track_publisher')));
            $track->setTitle($fixField($this->getDomVal($t, 'track_title')));
            $track->setGenre($fixField($this->getDomVal($t, 'track_genre')));
            $track->setMedia($fixField($this->getDomVal($t, 'track_media')));
            $track->setDiscNr($fixField($this->getDomVal($t, 'track_num_disc_num')));
            $track->setAlbumNr($fixField($this->getDomVal($t, 'track_num_album_num')));
            $track->setDuration($fixField($this->getDomVal($t, 'track_duration')));

            $release->addTrack($track);
        }

        return $release;
    }

    protected function getDomElement($dom, $name)
    {
        $element = $dom->getElementsByTagName($name)->item(0);

        if (!$element)
        {
            throw new DomainException(sprintf('Xml element missing: "%s"', $name));
        }

        return $element;
    }

    protected function getDomVal($dom, $name)
    {
        $element = $dom->getElementsByTagName($name)->item(0);

        if (!$element)
        {
            throw new DomainException(sprintf('Xml element missing: "%s"', $name));
        }

        return $element->nodeValue;
    }
}