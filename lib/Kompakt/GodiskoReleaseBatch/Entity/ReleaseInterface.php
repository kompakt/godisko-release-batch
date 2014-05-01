<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Entity;

use Kompakt\MediaDeliveryFramework\Entity\ReleaseInterface as GenericReleaseInterface;
use Kompakt\GodiskoReleaseBatch\Entity\TrackInterface;

interface ReleaseInterface extends GenericReleaseInterface
{
    public function setLabel($label);
    public function getLabel();
    public function setName($name);
    public function getName();
    public function setEan($ean);
    public function getEan();
    public function setCatalogNumber($catalogNumber);
    public function getCatalogNumber();
    public function setPhysicalReleaseDate(\DateTime $physicalReleaseDate);
    public function getPhysicalReleaseDate();
    public function setDigitalReleaseDate(\DateTime $digitalReleaseDate);
    public function getDigitalReleaseDate();
    public function setOriginalFormat($originalFormat);
    public function getOriginalFormat();
    public function setShortInfoEn($shortInfoEn);
    public function getShortInfoEn();
    public function setShortInfoDe($shortInfoDe);
    public function getShortInfoDe();
    public function setInfoEn($infoEn);
    public function getInfoEn();
    public function setInfoDe($infoDe);
    public function getInfoDe();
    public function setSaleTerritories($saleTerritories);
    public function getSaleTerritories();
    public function setBundleRestriction($bundleRestriction);
    public function getBundleRestriction();
    public function addTrack(TrackInterface $track);
    public function getTracks();
}