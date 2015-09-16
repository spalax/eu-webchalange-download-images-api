<?php
namespace Application\Service\Instagram\Images;

use Application\Data\Gallery\SourceNameInterface;
use Zend\Di\Di;

class CollectionFactory
{
    /**
     * @var Di
     */
    protected $di = null;

    /**
     * @param Di $di
     */
    public function __construct(Di $di)
    {
        $this->di = $di;
    }

    /**
     * @param SourceNameInterface $sourceNameData
     *
     * @return CollectionInterface
     */
    public function createCollection(SourceNameInterface $sourceNameData)
    {
        if (!is_null($sourceNameData->getUsername()) ||
            $sourceNameData->getSource() === SourceNameInterface::SOURCE_USER) {
            return $this->di->get('Application\Service\Instagram\Images\UserCollectionService',
                                    array('nameData'=>$sourceNameData));
        }

        return $this->di->get('Application\Service\Instagram\Images\FeedCollectionService');
    }
}
