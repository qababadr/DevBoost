<?php

namespace BadrQaba\DevBoost\Exception;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class ValidationException extends Exception
{
    private string $errorMessage;
    private array $errors;

    public function __construct(
        string $errorMessage,
        array $errors = [],
        int $httpCode = HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY
    ) {
        parent::__construct($errorMessage, $httpCode);
        $this->errorMessage = $errorMessage;
        $this->errors = $errors;
    }
    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): Response
    {
        $data = [];

        if (count($this->errors) === 0) {
            $data = [
                'error_message' => $this->errorMessage
            ];
        } else {
            $data = [
                'error_message' => $this->errorMessage,
                'errors' => $this->errors,
            ];
        }
        return response(
            $data,
            $this->getCode(),
        );
    }
}
