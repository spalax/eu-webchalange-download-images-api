<?php
namespace Application\Service\Instagram\Images;

class FeedCollectionService extends AbstractCollectionService
{
    /**
     * @param int $limit
     *
     * @return array
     */
    protected function fetch( $limit )
    {
        return $this->instagramWrapper->getUserFeed($limit);
    }
}
