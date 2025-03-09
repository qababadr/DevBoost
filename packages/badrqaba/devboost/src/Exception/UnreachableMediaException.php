<?php

namespace BadrQaba\DevBoost\Exception;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class UnreachableMediaException extends Exception
{

    public function __construct(
        string $message = "Unreachable Media",
        int $httpCode = HttpFoundationResponse::HTTP_REQUEST_TIMEOUT
    ) {
        parent::__construct($message, $httpCode);
    }
    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): Response
    {
        return response(
            [
                "error_message" => $this->getMessage()
            ],
            $this->getCode(),
        );
    }
}
