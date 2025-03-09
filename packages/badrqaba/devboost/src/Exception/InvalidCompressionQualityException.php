<?php

namespace BadrQaba\DevBoost\Exception;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class InvalidCompressionQualityException extends Exception
{

    public function __construct(
        string $message = "Invalid compression quality",
        int $httpCode = HttpFoundationResponse::HTTP_BAD_REQUEST
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
