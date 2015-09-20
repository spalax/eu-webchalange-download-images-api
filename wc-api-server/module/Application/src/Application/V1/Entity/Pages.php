<?php

namespace Application\V1\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pages
 *
 * @ORM\Table(name="pages", uniqueConstraints={@ORM\UniqueConstraint(name="uuid_UNIQUE", columns={"uuid"})})
 * @ORM\Entity
 */
class Pages implements PageInterface
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
     * @ORM\Column(name="uuid", type="string", length=40, nullable=false)
     */
    private $uuid;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text", length=65535, nullable=false)
     */
    private $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="error_message", type="string", length=200, nullable=true)
     */
    private $errorMessage;

    /**
     * @ORM\OneToMany(targetEntity="Application\V1\Entity\Images",
     *                mappedBy="page")
     */
    private $images;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_images_cnt", type="smallint", nullable=false)
     */
    private $totalImagesCnt;

    /**
     * @var integer
     *
     * @ORM\Column(name="pending_images_cnt", type="smallint", nullable=false)
     */
    private $pendingImagesCnt;


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
     * Set uuid
     *
     * @param string $uuid
     *
     * @return Pages
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Pages
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set status
     *
     * @param int $status
     *
     * @return Pages
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatusNumeric()
    {
        return $this->status;
    }

    /**
     * Get status text
     *
     * @return string
     */
    public function getStatus()
    {
        switch ($this->status) {
            case PageInterface::STATUS_RECOVERING :
                return 'recovering';
            case PageInterface::STATUS_DONE :
                return 'done';
            case PageInterface::STATUS_PENDING :
                return 'pending';
            case PageInterface::STATUS_ERROR :
                return 'error';
            case PageInterface::STATUS_RUNNING :
                return 'running';
            default :
                return 'unknown';
        }
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add image
     *
     * @param \Application\V1\Entity\Images $image
     *
     * @return Pages
     */
    public function addImage(\Application\V1\Entity\Images $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \Application\V1\Entity\Images $image
     */
    public function removeImage(\Application\V1\Entity\Images $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set totalImagesCnt
     *
     * @param integer $totalImagesCnt
     *
     * @return Pages
     */
    public function setTotalImagesCnt($totalImagesCnt)
    {
        $this->totalImagesCnt = $totalImagesCnt;

        return $this;
    }

    /**
     * Get totalImagesCnt
     *
     * @return integer
     */
    public function getTotalImagesCnt()
    {
        return $this->totalImagesCnt;
    }

    /**
     * Set pendingImagesCnt
     *
     * @param integer $pendingImagesCnt
     *
     * @return Pages
     */
    public function setPendingImagesCnt($pendingImagesCnt)
    {
        $this->pendingImagesCnt = $pendingImagesCnt;

        return $this;
    }

    /**
     * Get pendingImagesCnt
     *
     * @return integer
     */
    public function getPendingImagesCnt()
    {
        return $this->pendingImagesCnt;
    }
}
