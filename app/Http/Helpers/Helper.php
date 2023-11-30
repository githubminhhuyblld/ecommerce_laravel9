<?php

namespace App\Http\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class Helper
{

    /**
     * Sends an error response to the user.
     *
     * @param string $message The error message to display.
     * @param array $error Additional error data (optional).
     * @param int $code The HTTP status code to send (optional, defaults to 401).
     *
     * @return void
     */
    public static function sendError($message, $error = [], $code = 401)
    {
        $response = [
            'success' => false,
            'message' => $message
        ];
        if (!empty($error)) {
            $response['data'] = $error;
        }
        throw new HttpResponseException(response()->json($response, $code));
    }

    /**
     * Sends a "not found" error response to the model.
     *
     * @param string $model The name of the model that was not found.
     * @param int $id The ID of the model that was not found.
     *
     * @return \Illuminate\Http\Response
     */
    public static function sendNotFoundMessage($model, $id)
    {
        return response()->json(['message' => "$model ID: {$id} not found"], Response::HTTP_NOT_FOUND);
    }
}
