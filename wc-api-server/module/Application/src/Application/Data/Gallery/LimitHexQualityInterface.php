<?php
namespace Application\Data\Gallery;

interface LimitHexQualityInterface extends QualityInterface, HexInterface
{
    /**
     * @return int
     */
    public function getLimit();
}
