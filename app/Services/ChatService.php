<?php

namespace App\Services;

use App\DTO\OpenaiMessage;
use App\Events\BotResponseReceived;
use App\Models\UserChat;
use App\Services\Api\Openai;

class ChatService
{
    protected Openai $openai;

    protected string $instruction = "You are a professional nutritionist. Respond to user inquiries specifically within the field of nutrition, diet, health-related food guidance, meal planning, and nutrient information. Politely redirect any requests outside these topics by suggesting they consult the relevant expert or resource. Keep answers practical, evidence-based, and aligned with current nutrition science.";

    public function __construct(Openai $openai)
    {
        $this->openai = $openai;
    }

    public function chat(int $user, string $text): void
    {
        $userChat = $this->getExistingChat($user);

        $messages = $this->getMessages($userChat, $text);
        $messages
            ->addMessage('system', $this->instruction);

        $response = $this->openai->chat($messages);

        $updatedChat = $this->storeChat($userChat, $messages, $response);

        BotResponseReceived::dispatch($updatedChat);
    }

    protected function getExistingChat(int $userId): UserChat
    {
        return UserChat::firstOrCreate(
            ['user_id' => $userId],
        );
    }

    protected function getMessages(UserChat $userChat, string $text): OpenaiMessage
    {
        $chat = json_decode($userChat->chat, true);

        $messages = new OpenaiMessage($chat);
        $messages->addMessage('user', $text);

        return $messages;
    }

    protected function storeChat(UserChat $userChat, OpenaiMessage $messages, string $response): UserChat
    {
        $messages
            ->addMessage('assistant', $response);

        $chat = $messages->getMessages();

        $userChat->chat = $chat;
        $userChat->save();

        return $userChat;
    }
}
