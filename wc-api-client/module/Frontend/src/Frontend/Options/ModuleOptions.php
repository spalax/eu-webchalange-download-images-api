<?php
namespace Frontend\Options;

use Frontend\Options\Exception\DirectoryNotWritableOrNotExistsException;
use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $apiUrl = null;

    /**
     * @return string
     */
    public function getApiUrl()
    {
        if (is_null($this->apiUrl) && !empty($_SERVER['HTTP_HOST'])) {
            return 'http://'.$_SERVER['HTTP_HOST'];
        }
        return $this->apiUrl;
    }

    /**
     * @param string $apiUrl
     */
    public function setApiUrl( $apiUrl )
    {
        $this->apiUrl = $apiUrl;
    }
}
