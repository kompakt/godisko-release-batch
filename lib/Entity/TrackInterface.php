<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Entity;

use Kompakt\Mediameister\Entity\TrackInterface as MeisterTrackInterface;

interface TrackInterface extends MeisterTrackInterface
{
    public function setIsrc($isrc);
    public function getIsrc();
    public function setPosition($position);
    public function getPosition();
    public function setArtist($artist);
    public function getArtist();
    public function setComposer($composer);
    public function getComposer();
    public function setSongwriter($songwriter);
    public function getSongwriter();
    public function setPublisher($publisher);
    public function getPublisher();
    public function setTitle($title);
    public function getTitle();
    public function setGenre($genre);
    public function getGenre();
    public function setMedia($media);
    public function getMedia();
    public function setDiscNr($discNr);
    public function getDiscNr();
    public function setAlbumNr($albumNr);
    public function getAlbumNr();
    public function setDuration($duration);
    public function getDuration();
}