<?php
namespace Application\V1\Entity;

interface PageInterface {
    const STATUS_PENDING = 1;
    const STATUS_RUNNING = 2;
    const STATUS_RECOVERING  = 4;
    const STATUS_DONE    = 5;
    const STATUS_ERROR   = 6;
}
