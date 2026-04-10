<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(private ChatService $chat) {}

    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'min:2', 'max:500'],
        ]);

        $message   = strip_tags($request->input('message'));
        $sessionId = $request->session()->getId();

        try {
            $reply = $this->chat->sendMessage($message, $sessionId);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => 'Service unavailable. Please try again later.'], 503);
        }

        return response()->json(['reply' => $reply]);
    }

    public function clear(Request $request): JsonResponse
    {
        $this->chat->clearHistory($request->session()->getId());

        return response()->json(['ok' => true]);
    }
}
