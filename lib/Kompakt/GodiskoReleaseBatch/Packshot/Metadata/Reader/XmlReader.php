<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader;

use Kompakt\ReleaseBatch\Entity\Release;
use Kompakt\ReleaseBatch\Entity\Track;
use Kompakt\ReleaseBatch\Packshot\Metadata\Reader\ReaderInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Exception\DomainException;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Exception\InvalidArgumentException;

class XmlReader implements ReaderInterface
{
    protected $releasePrototype = null;
    protected $trackPrototype = null;
    protected $file = null;

    public function __construct(Release $releasePrototype, Track $trackPrototype, $file)
    {
        $info = new \SplFileInfo($file);

        if (!$info->isFile())
        {
            throw new InvalidArgumentException(sprintf('Godisko metadata Xml file not found'));
        }

        if (!$info->isReadable())
        {
            throw new InvalidArgumentException(sprintf('Godisko metadata Xml file not readable'));
        }

        $this->releasePrototype = $releasePrototype;
        $this->trackPrototype = $trackPrototype;
        $this->file = $file;
    }

    public function load()
    {
        return $this->loadXml($this->fixXml($this->loadFile()));
    }

    protected function loadFile()
    {
        $handle = fopen($this->file, 'r');
        $xml = fread($handle, filesize($this->file));
        fclose($handle);
        return $xml;
    }

    protected function fixXml($xml)
    {
        $patterns = array(
            '/\\x00/' => '', // use bin2hex to find hexadecimal representation: echo bin2hex('Ê');
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
            '/&#8233;/' => ' ', # 0x2029 - linefeed - '/ /' (cause of hotmail problem)
            '/†/' => '' // &#x2020; &dagger;
        );

        $convertToUtf = function($matches)
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
        $xml = preg_replace_callback('/<(\w*)>([^<>]*)<\/\w*>/', $convertToUtf, $xml);
        return preg_replace('/ISO-8859-1/i', 'utf-8', $xml);
    }

    protected function loadXml($xml)
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
            $s = preg_replace('/\s+/', " ", trim($s));
            #$s = mb_convert_case(trim($s), MB_CASE_TITLE);
            #$s = preg_replace('/(\'|\xB4)S/', '\'s', $s);
            return $s;
        };

        $fixReleaseDate = function($releaseDate)
        {
            preg_match('/(\d{4,4})(\d{2,2})(\d{2,2})/', $releaseDate, $matches);

            if(isset($matches[1]) && isset($matches[2]) && isset($matches[3]))
            {
                $date = $matches[1].'-'.$matches[2].'-'.$matches[3];
                return \DateTime::createFromFormat('Y-m-d', $date);
            }
            
            return '0000-00-00';
        };

        $fixBundleRestriction = function($bundleRestriction)
        {
            return (preg_match('/^True$/i', $bundleRestriction)) ? 1 : 0;
        };

        $fixTrackPublisher = function($publisher)
        {
            if (preg_match('/copyright control/i', $publisher))
            {
                return 'Copyright Control';
            }

            if (preg_match('/^none$/i', $publisher))
            {
                return '';
            }

            return $publisher;
        };

        $release = clone $this->releasePrototype;

        $release
            ->setLabel($fixField($this->getDomVal($dom, 'labelname')))
            ->setName($fixField($this->getDomVal($dom, 'release_name')))
            ->setEan($fixField($this->getDomVal($dom, 'release_ean')))
            ->setCatalogNumber($fixField($this->getDomVal($dom, 'release_catno')))
            ->setPhysicalReleaseDate($fixReleaseDate($this->getDomVal($dom, 'release_physical_releasedate')))
            ->setDigitalReleaseDate($fixReleaseDate($this->getDomVal($dom, 'release_digital_releasedate')))
            ->setOriginalFormat($fixField($this->getDomVal($dom, 'release_originalformat')))
            ->setShortInfoEn($fixField($this->getDomVal($dom, 'release_short_info_e')))
            ->setShortInfoDe($fixField($this->getDomVal($dom, 'release_short_info_d')))
            ->setInfoEn($fixField($this->getDomVal($dom, 'release_info_e')))
            ->setInfoDe($fixField($this->getDomVal($dom, 'release_info_d')))
            ->setSaleTerritories($fixField($this->getDomVal($dom, 'release_saleterritories')))
            ->setBundleRestriction($fixBundleRestriction($this->getDomVal($dom, 'release_bundlerestriction')))
        ;

        $tracks = $this->getDomElement($dom, 'tracks');

        foreach ($tracks->getElementsByTagName('track') as $t)
        {
            $track = clone $this->trackPrototype;

            $track
                ->setIsrc($fixField($this->getDomVal($t, 'track_isrc')))
                ->setPosition($fixField($this->getDomVal($t, 'track_originalposition')))
                ->setArtist($fixField($this->getDomVal($t, 'track_artist')))
                ->setComposer($fixField($this->getDomVal($t, 'track_composer')))
                ->setSongwriter($fixField($this->getDomVal($t, 'track_songwriter')))
                ->setPublisher($fixField($fixTrackPublisher($this->getDomVal($t, 'track_publisher'))))
                ->setTitle($fixField($this->getDomVal($t, 'track_title')))
                ->setGenre($fixField($this->getDomVal($t, 'track_genre')))
                ->setMedia($fixField($this->getDomVal($t, 'track_media')))
                ->setDiscNr($fixField($this->getDomVal($t, 'track_num_disc_num')))
                ->setAlbumNr($fixField($this->getDomVal($t, 'track_num_album_num')))
                ->setDuration($fixField($this->getDomVal($t, 'track_duration')))
            ;

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