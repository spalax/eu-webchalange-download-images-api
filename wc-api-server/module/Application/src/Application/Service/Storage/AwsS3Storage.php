<?php
namespace Application\Service\Storage;

use Aws\S3\S3Client;

class AwsS3Storage implements StorageInterface
{
    /**
     * @var S3Client
     */
    protected $s3Client;

    /**
     * @var string
     */
    protected $backet;

    /**
     * @param S3Client $s3Client
     */
    public function __construct(S3Client $s3Client, $options)
    {
        if (!array_key_exists('backet', $options)) {
            throw new \DomainException('backet must be defined for AwsS3Client');
        }

        $this->backet = $options['backet'];

        $this->s3Client = $s3Client;
    }

    /**
     * @param string $fileExtension
     * @param string $data
     *
     * @return string
     */
    public function store($fileExtension, $data)
    {
        /* @var $result \Aws\Result */
        $result = $this->s3Client->upload($this->backet, uniqid().'.'.$fileExtension, $data);

        return $result->get('ObjectURL');
    }
}
