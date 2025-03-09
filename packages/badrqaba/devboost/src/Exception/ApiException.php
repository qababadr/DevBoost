<?php

namespace BadrQaba\DevBoost\Exception;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class ApiException extends Exception
{
    private mixed $data;

    public function __construct(
        string $message = "",
        mixed $data = null,
        int $httpCode = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR
    ) {
        parent::__construct($message, $httpCode);
        $this->data = $data;
    }
    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): Response
    {
        return response(
            [
                "data" => $this->data,
                "error_message" => $this->getMessage()
            ],
            $this->getCode(),
        );
    }
}
