<?php

namespace App\Helpers;

use Exception;

class FormOpenaiJson
{
    public static function toArray(string $data): array
    {
        // Remove the code block formatting (backticks and "json" label)
        $inputString = preg_replace('/```json\n|\n```/', '', $data);

        // Decode and re-encode to validate JSON format
        $decodedData = json_decode($inputString, true);

        // Check if the decoding was successful
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON format: ' . json_last_error_msg());
        }

        // Return the formatted JSON
        return $decodedData;
    }
}
