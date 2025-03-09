<?php

namespace BadrQaba\DevBoost\Api\Core;

use Illuminate\Contracts\Validation\Validator;
use BadrQaba\DevBoost\Exception\ValidationException;

trait ResponseFormatter
{
    /**
     * Create a standardized error response.
     *
     * @param string $errorMessage the main error message.
     * @param array $errors Array of error messages.
     * @return array The representation of the error
     */
    public function errorResponse(string $errorMessage, array $errors = []): array
    {
        if (count($errors) === 0) {
            return [
                'error_message' => $errorMessage
            ];
        } else {
            return [
                'error_message' => $errorMessage,
                'errors' => $errors,
            ];
        }
    }


    /**
     * Create a standardized success response.
     * @param mixed $data the data to be sent
     * @param array $extra extra data if needed
     * @return array the representation of the success data
     */
    public function successResponse(mixed $data, array $extra = []): array
    {
        if (count($extra) === 0) {
            return [
                'data' => $data
            ];
        } else {
            return [
                'data' => $data,
                'extra' => $extra
            ];
        }
    }

    /**
     * Generate a validation error based response
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \BadrQaba\DevBoost\Exception\ValidationException
     * @return never
     */
    public function validationErrorResponse(Validator $validator)
    {
        $errors = $validator->getMessageBag()->toArray();

        $errorResponse = [
            "error_message" => $validator->getMessageBag()->first(),
            "errors" => []
        ];

        foreach ($errors as $field => $messages) {
            $errorResponse['errors'][$field] = $messages;
        }

        throw new ValidationException(
            $errorResponse['error_message'],
            $errorResponse['errors']
        );
    }
}
