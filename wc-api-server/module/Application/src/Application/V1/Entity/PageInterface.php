<?php
namespace Application\V1\Entity;

interface PageInterface {
    const STATUS_PENDING = 1;
    const STATUS_RUNNING = 2;
    const STATUS_BURIED  = 4;
    const STATUS_DONE    = 5;
}
