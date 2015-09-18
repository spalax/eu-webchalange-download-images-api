<?php

namespace Application\V1\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Images
 *
 * @ORM\Table(name="images", indexes={@ORM\Index(name="fk_site_id", columns={"page_id"})})
 * @ORM\Entity
 */
class Images
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="remote_path", type="text", length=65535, nullable=false)
     */
    private $remotePath;

    /**
     * @var string
     *
     * @ORM\Column(name="local_path", type="string", length=500, nullable=false)
     */
    private $localPath;

    /**
     * @var integer
     *
     * @ORM\Column(name="width", type="smallint", nullable=false)
     */
    private $width;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="smallint", nullable=false)
     */
    private $height;

    /**
     * @var integer
     *
     * @ORM\Column(name="size", type="integer", nullable=false)
     */
    private $size;

    /**
     * @var string
     *
     * @ORM\Column(name="content_type", type="string", length=200, nullable=false)
     */
    private $contentType;

    /**
     * @var \Application\V1\Entity\Pages
     *
     * @ORM\ManyToOne(targetEntity="Application\V1\Entity\Pages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     * })
     */
    private $page;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set remotePath
     *
     * @param string $remotePath
     *
     * @return Images
     */
    public function setRemotePath($remotePath)
    {
        $this->remotePath = $remotePath;

        return $this;
    }

    /**
     * Get remotePath
     *
     * @return string
     */
    public function getRemotePath()
    {
        return $this->remotePath;
    }

    /**
     * Set localPath
     *
     * @param string $localPath
     *
     * @return Images
     */
    public function setLocalPath($localPath)
    {
        $this->localPath = $localPath;

        return $this;
    }

    /**
     * Get localPath
     *
     * @return string
     */
    public function getLocalPath()
    {
        return $this->localPath;
    }

    /**
     * Set width
     *
     * @param integer $width
     *
     * @return Images
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return Images
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set size
     *
     * @param integer $size
     *
     * @return Images
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set contentType
     *
     * @param string $contentType
     *
     * @return Images
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get contentType
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set page
     *
     * @param \Application\V1\Entity\Pages $page
     *
     * @return Images
     */
    public function setPage(\Application\V1\Entity\Pages $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Application\V1\Entity\Pages
     */
    public function getPage()
    {
        return $this->page;
    }
}
