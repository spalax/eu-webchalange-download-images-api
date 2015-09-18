<?php

namespace Application\V1\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Queue
 *
 * @ORM\Table(name="queue", indexes={@ORM\Index(name="pop", columns={"status", "queue", "scheduled"}), @ORM\Index(name="prune", columns={"status", "queue", "finished"})})
 * @ORM\Entity
 */
class Queue
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
     * @ORM\Column(name="queue", type="string", length=64, nullable=false)
     */
    private $queue;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="text", length=65535, nullable=false)
     */
    private $data;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=false)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created = '0000-00-00 00:00:00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="scheduled", type="datetime", nullable=false)
     */
    private $scheduled = '0000-00-00 00:00:00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="executed", type="datetime", nullable=true)
     */
    private $executed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finished", type="datetime", nullable=true)
     */
    private $finished;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", length=65535, nullable=true)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="trace", type="text", length=65535, nullable=true)
     */
    private $trace;



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
     * Set queue
     *
     * @param string $queue
     *
     * @return Queue
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * Get queue
     *
     * @return string
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Set data
     *
     * @param string $data
     *
     * @return Queue
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Queue
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Queue
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set scheduled
     *
     * @param \DateTime $scheduled
     *
     * @return Queue
     */
    public function setScheduled($scheduled)
    {
        $this->scheduled = $scheduled;

        return $this;
    }

    /**
     * Get scheduled
     *
     * @return \DateTime
     */
    public function getScheduled()
    {
        return $this->scheduled;
    }

    /**
     * Set executed
     *
     * @param \DateTime $executed
     *
     * @return Queue
     */
    public function setExecuted($executed)
    {
        $this->executed = $executed;

        return $this;
    }

    /**
     * Get executed
     *
     * @return \DateTime
     */
    public function getExecuted()
    {
        return $this->executed;
    }

    /**
     * Set finished
     *
     * @param \DateTime $finished
     *
     * @return Queue
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * Get finished
     *
     * @return \DateTime
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Queue
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set trace
     *
     * @param string $trace
     *
     * @return Queue
     */
    public function setTrace($trace)
    {
        $this->trace = $trace;

        return $this;
    }

    /**
     * Get trace
     *
     * @return string
     */
    public function getTrace()
    {
        return $this->trace;
    }
}
