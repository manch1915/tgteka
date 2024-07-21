<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $conversations = Conversation::where(function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('user_one', '!=', auth()->id())
                    ->orWhere('user_two', '!=', auth()->id());
            })
                ->when($search, function ($query) use ($search) {
                    $query->whereHas('userOne', function ($query) use ($search) {
                        $query->where('username', 'like', "%$search%");
                    })
                        ->orWhereHas('userTwo', function ($query) use ($search) {
                            $query->where('username', 'like', "%$search%");
                        });
                });
        })
            ->with('userOne', 'userTwo')
            ->get();

        $formattedConversations = $conversations->map(function (Conversation $conversation) {
            $otherUser = $conversation->userOne->id === auth()->id()
                ? $conversation->userTwo->toArray()
                : $conversation->userOne->toArray();

            $unreadCount = $conversation->messages()
                ->where('user_id', '!=', auth()->id())
                ->where('is_seen', false)
                ->count();

            return [
                'id'   => $conversation->id,
                'user' => $otherUser,
                'last_message' => $conversation->messages()->latest()->first(),
                'unread_count' => $unreadCount,
            ];
        });

        return response()->json($formattedConversations);
    }

    public function conversationsMessages($conversationId)
    {
        $conversationMessages = ConversationMessages::where('conversation_id' , $conversationId)
            ->with('user:id,username,email')
            ->get();
        $conversation = Conversation::with('messages')->findOrFail($conversationId);

        $conversation->messages()
            ->where('user_id', '!=', auth()->id())
            ->where('is_seen', false)
            ->update(['is_seen' => true]);

        foreach ($conversationMessages as $message) {
            $image = $message->getFirstMedia('personal_message_images');
            $message->content_type = ($image) ? 'image' : 'text';
            $message->message = ($image) ? $image->getUrl() : $message->message;
        }

        return response()->json($conversationMessages);
    }

}
