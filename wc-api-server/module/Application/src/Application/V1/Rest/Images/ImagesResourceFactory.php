<?php
namespace Application\V1\Rest\Images;

class ImagesResourceFactory
{
    public function __invoke($services)
    {
        return new ImagesResource($services->get('Doctrine\ORM\EntityManager'));
    }
}
