<?php
namespace Application\Service\Instagram\Images;

use Application\Data\Gallery\LimitHexQualityInterface;

interface CollectionInterface
{
    /**
     * @param LimitHexQualityInterface $limitHexQualityData
     *
     * @return mixed
     */
    public function getImages( LimitHexQualityInterface $limitHexQualityData );
}
