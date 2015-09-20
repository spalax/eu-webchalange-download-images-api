<?php
namespace Frontend\API\Rest\Resources;

use Frontend\API\Rest\Client as RestClient;
use Frontend\API\Rest\Resources\Exception\ApiException;
use Frontend\Exception\DomainException;

abstract class AbstractResource
{
    /**
     * @var RestClient
     */
    protected $restClient;

    /**
     * @param RestClient $restClient
     */
    public function __construct(RestClient $restClient)
    {
        $this->restClient = $restClient;
    }

    /**
     * @return string
     */
    protected abstract function getPath();

    /**
     * @return string
     */
    protected abstract function getResourceName();

    /**
     * @param array $data
     * @return array
     */
    protected abstract function extractItem($data);

    /**
     * @param array $responseData
     *
     * @throws ApiException
     */
    protected function assertError($responseData)
    {
        if (array_key_exists('status', $responseData) && is_numeric($responseData['status']) &&
            ((int)$responseData['status'] >= 300 || (int)$responseData['status'] < 200)) {
            throw new ApiException('Remote Service Error : ' . $responseData['title'].' - '.$responseData['detail'], $responseData['status']);
        }
    }

    /**
     * @return object
     */
    protected function fetchCollection($path, $page = null, $limit = null)
    {
        $params = [];
        if (!is_null($page)) {
            $params['page'] = $page;
        }
        if (!is_null($limit)) {
            $params['limit'] = $limit;
        }

        $data = $this->restClient->get($path, $params);

        $this->assertError($data);

        if (!array_key_exists('_embedded', $data)) {
            throw new DomainException('Invalid response from pages, does not contains _embedded');
        }

        if (!array_key_exists($this->getResourceName(), $data['_embedded'])) {
            throw new DomainException('Invalid response from ' . $this->getResourceName() . ', does not contains pages in _embedded');
        }

        return (object)['data'=>$data['_embedded'][$this->getResourceName()],
                        'page_count'=>$data['page_count'],
                        'page_size'=>$data['page_size'],
                        'total_items'=>$data['total_items'],
                        'has_prev'=>array_key_exists('prev', $data['_links']),
                        'has_next'=>array_key_exists('next', $data['_links']),
                        'page'=>$data['page']];
    }

    /**
     * @param string $id
     *
     * @return array
     */
    public function getItem($id)
    {
        $data = $this->restClient->get($this->getPath().'/'.$id);
        $this->assertError($data);

        return $this->extractItem($data);
    }
}
