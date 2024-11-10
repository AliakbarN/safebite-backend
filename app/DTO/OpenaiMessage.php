<?php

namespace App\DTO;

class OpenaiMessage
{
    protected array $messages;

    public function __construct(array $messages = [])
    {
        $this->messages = $messages;
    }

    public function addMessage(string $role, string $content): self
    {
        $message = ['role' => $role, 'content' => $content];

        $this->messages[] = $message;

        return $this;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
