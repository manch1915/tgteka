<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationMessages;
use Illuminate\Support\Facades\Log;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = auth()->user()->conversations;

        $formattedConversations = $conversations->map(function (Conversation $conversation) {
            $otherUser = $conversation->userOne->id === auth()->id()
                ? $conversation->userTwo->toArray()
                : $conversation->userOne->toArray();

            return [
                'id'   => $conversation->id,
                'user' => $otherUser,
                'last_message' => $conversation->messages()->latest()->first()
            ];
        });

        return response()->json($formattedConversations);
    }

    public function conversationsMessages($conversationId)
    {
        $conversationMessages = ConversationMessages::where('conversation_id' , $conversationId)
            ->with('user:id,name,email')
            ->get();

        foreach ($conversationMessages as $message) {
            $seed = $message->user->name ?? $message->user->email;
            $message->user->profile_photo_url = $this->getAvatarUrl($seed);
        }

        return response()->json($conversationMessages);
    }

    private function getAvatarUrl($seed) //Make sure the $seed is URL-encoded
    {
        if (filter_var($seed, FILTER_VALIDATE_EMAIL)) {
            $seed = explode('@', $seed)[0];
        }
        return "https://api.dicebear.com/7.x/initials/svg?seed={$seed}";
    }
}
