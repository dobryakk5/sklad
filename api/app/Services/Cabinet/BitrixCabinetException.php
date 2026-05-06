<?php

namespace App\Services\Cabinet;

use RuntimeException;

final class BitrixCabinetException extends RuntimeException
{
    public function __construct(
        private readonly string $errorCode,
        private readonly int $httpStatus = 502,
        ?string $message = null,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message ?? $errorCode, 0, $previous);
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }
}
