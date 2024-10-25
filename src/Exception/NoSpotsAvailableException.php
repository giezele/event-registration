<?php

declare(strict_types=1);

namespace App\Exception;

class NoSpotsAvailableException extends \Exception
{
    public function __construct(
        string $message = "No spots available for this event.",
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
