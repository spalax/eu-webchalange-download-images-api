<?php
namespace Application\Service\Storage;


interface StorageInterface
{
    /**
     * @param string $fileExtension
     * @param string $data
     *
     * @return string
     */
    public function store($fileExtension, $data);
}
