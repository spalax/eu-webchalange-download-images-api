<?php
namespace Application\Service\Storage;

class LocalStorage implements StorageInterface
{
    /**
     * @var string
     */
    protected $httpPath;

    /**
     * @var string
     */
    protected $fsPath;

    /**
     * @param array $options
     */
    public function __construct($options)
    {
        if (!array_key_exists('fsPath', $options) ||
            !is_writable($options['fsPath']) ||
            !is_readable($options['fsPath'])) {
            throw new \DomainException('fsPath must be defined, writable and readable');
        }

        if (!array_key_exists('httpPath', $options)) {
            throw new \DomainException('httpPath must be defined');
        }

        $this->httpPath = $options['httpPath'];
        $this->fsPath = $options['fsPath'];
    }

    /**
     * @return bool|string
     */
    protected function generatePath()
    {
        return date('Y/m/d/h/i');
    }

    /**
     * @param string $fileExtension
     * @param string $data
     *
     * @return string
     */
    public function store($fileExtension, $data)
    {
        $generatedPath = $this->generatePath();
        $path = $this->fsPath.'/'.$generatedPath;
        if (!is_dir($path)) {
            if (!mkdir($path, 0777, true)) {
                throw new \DomainException('Could not create directory ['.$path.'] for image save');
            }
        }
        $filename = uniqid().'.'.$fileExtension;
        $fullPath = $path.'/'.$filename;

        $fp = fopen($fullPath, "wb");
        if (!$fp) {
            throw new \DomainException('Could not open resource ['.$path.'] for write');
        }

        fwrite($fp, $data );
        fclose($fp);

        return $this->httpPath.'/'.$generatedPath.'/'.$filename;
    }
}
