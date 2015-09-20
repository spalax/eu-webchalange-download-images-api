<?php
namespace Frontend\API\Rest\Resources;

class ImagesResource extends AbstractResource
{
    /**
     * @var string
     */
    protected $path = '/v1/pages/:page_id/images';

    /**
     * @return string
     */
    protected function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    protected function getResourceName()
    {
        return 'images';
    }

    /**
     * @param string $id
     * @param int [optional] $page
     * @param int [optional] $limit
     *
     * @return object
     */
    public function getCollection($id, $page = null, $limit = null)
    {
        return $this->fetchCollection(str_replace(':page_id', $id, $this->getPath()), $page, $limit);
    }

    /**
     * @param array $data
     *
     * @return object
     */
    protected function extractItem($data)
    {
        return (object)$data;
    }
}
