<?php
namespace Frontend\API\Rest\Resources;

class PagesResource extends AbstractResource
{
    /**
     * @var string
     */
    protected $path = '/v1/pages';

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
        return 'pages';
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

    /**
     * @param int [optional] $page
     * @param int [optional] $limit
     *
     * @return object
     */
    public function getCollection($page = null, $limit = null)
    {
        return $this->fetchCollection($this->getPath(), $page, $limit);
    }

    /**
     * @param $siteUrl
     *
     * @return mixed
     */
    public function download($siteUrl)
    {
        $data = $this->restClient->request('post', $this->getPath(), ['site_url'=>$siteUrl]);
        $this->assertError($data);
        return $data;
    }
}
