<?php

namespace App\Services\Api;

use App\DTO\OpenaiMessage;
use App\Enums\OpenaiModels;
use OpenAI\Client;

class Openai
{
    protected Client $openai;

    public function __construct(string $key)
    {
        $this->openai =  \OpenAI::client($key);
    }

    public function chat(OpenaiMessage $message): string
    {
        $result = $this->openai->chat()->create([
            'model' => OpenaiModels::BaseModal,
            'messages' => [...$message->getMessages()],
        ]);

        return $result->choices[0]->message->content;
    }
}
