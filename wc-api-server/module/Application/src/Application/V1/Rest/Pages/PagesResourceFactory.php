<?php
namespace Application\V1\Rest\Pages;

class PagesResourceFactory
{
    public function __invoke($services)
    {
        return new PagesResource($services->get('Doctrine\ORM\EntityManager'),
                                 $services->get('SlmQueue\Queue\QueuePluginManager')
                                          ->get('ParsePageQueue'),
                                 $services->get('SlmQueue\Job\JobPluginManager'));
    }
}
