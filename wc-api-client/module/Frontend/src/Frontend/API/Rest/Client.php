<?php
namespace Frontend\API\Rest;

use Frontend\Options\ModuleOptions;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Client as HttpClient;

class Client
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var HttpRequest
     */
    protected $httpRequest;

    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     * @param HttpClient $httpClient
     * @param HttpRequest $httpRequest
     * @param ModuleOptions $moduleOptions
     */
    public function __construct(HttpClient $httpClient,
                                HttpRequest $httpRequest,
                                ModuleOptions $moduleOptions)
    {
        $this->httpClient = $httpClient;
        $this->httpRequest = $httpRequest;
        $this->moduleOptions = $moduleOptions;

        $this->httpClient->getRequest()->getHeaders()->addHeaders(array(
            'Accept'       => 'application/json'
        ));
    }

    /**
     * @param string $url
     * @param array [optional] $params
     */
    public function get($url, $params = [])
    {
        return $this->request('get', $url, $params);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array [optional] $params
     */
    public function request($method, $url, $params = [])
    {
        $this->httpClient->setUri($this->moduleOptions->getApiUrl().'/'.ltrim($url, '/'));
        $this->httpClient->setMethod($method);

        if (!is_null($params)) {
            if ($method == 'post' || $method == 'put') {
                $this->httpClient->setEncType(HttpClient::ENC_FORMDATA);
                $this->httpClient->setParameterPost($params);
            } else {
                $this->httpClient->setEncType(HttpClient::ENC_URLENCODED);
                $this->httpClient->setParameterGet($params);
            }
        }


        $response = $this->httpClient->send();
        $data = json_decode($response->getBody(), true);
        return $data;
    }
}
