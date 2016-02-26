<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Entity;

use Kompakt\Mediameister\Entity\TrackInterface;

class Track implements TrackInterface
{
    protected $isrc = null;
    protected $uuid = null;
    protected $position = null;
    protected $artist = null;
    protected $composer = null;
    protected $songwriter = null;
    protected $publisher = null;
    protected $title = null;
    protected $genre = null;
    protected $media = null;
    protected $discNr = null;
    protected $albumNr = null;
    protected $duration = null;

    public function setIsrc($isrc)
    {
        $this->isrc = $isrc;
        return $this;
    }

    public function getIsrc()
    {
        return $this->isrc;
    }

    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setArtist($artist)
    {
        $this->artist = $artist;
        return $this;
    }

    public function getArtist()
    {
        return $this->artist;
    }

    public function setComposer($composer)
    {
        $this->composer = $composer;
        return $this;
    }

    public function getComposer()
    {
        return $this->composer;
    }

    public function setSongwriter($songwriter)
    {
        $this->songwriter = $songwriter;
        return $this;
    }

    public function getSongwriter()
    {
        return $this->songwriter;
    }

    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;
        return $this;
    }

    public function getPublisher()
    {
        return $this->publisher;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setGenre($genre)
    {
        $this->genre = $genre;
        return $this;
    }

    public function getGenre()
    {
        return $this->genre;
    }

    public function setMedia($media)
    {
        $this->media = $media;
        return $this;
    }

    public function getMedia()
    {
        return $this->media;
    }

    public function setDiscNr($discNr)
    {
        $this->discNr = $discNr;
        return $this;
    }

    public function getDiscNr()
    {
        return $this->discNr;
    }

    public function setAlbumNr($albumNr)
    {
        $this->albumNr = $albumNr;
        return $this;
    }

    public function getAlbumNr()
    {
        return $this->albumNr;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    public function getDuration()
    {
        return $this->duration;
    }
    
    public function __clone()
    {}
}