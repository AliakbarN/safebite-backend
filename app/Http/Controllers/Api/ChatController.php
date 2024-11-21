<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserChat;
use App\Services\Api\Openai;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\text;
use function Pest\Laravel\get;

class ChatController extends Controller
{
    protected ChatService $chatService;
    protected Response|ResponseFactory $response;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
        $this->response = response();
    }

    public function userSendMessage(Request $request): JsonResponse
    {
        $user = Auth::user();
        $text = $request->get('text') ?? '';

        $this->chatService->chat($user->id, $text);

        return $this->response->json([
            'message' => 'Chat updated',
        ]);
    }

    public function getChatHistory(): JsonResponse
    {
        $user = Auth::user();

        $userChat = $this->getUserChat($user->id);

        return $this->response->json([
            'chat' => $userChat->chat ?? [],
        ]);
    }

    protected function getUserChat(int $userId): UserChat
    {
        return UserChat::firstOrCreate(['user_id' => $userId]);
    }
}
