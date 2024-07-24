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

        // Retrieve conversations where the authenticated user is involved
        $conversations = Conversation::where(function ($query) use ($search) {
            $query->where('user_one', auth()->id())
                ->orWhere('user_two', auth()->id());
        })
            ->where(function ($query) use ($search) {
                $query->whereHas('userOne', function ($query) use ($search) {
                    $query->where('username', 'like', "%$search%");
                })
                    ->orWhereHas('userTwo', function ($query) use ($search) {
                        $query->where('username', 'like', "%$search%");
                    });
            })
            ->with('userOne', 'userTwo', 'messages') // Eager load userOne, userTwo, and messages
            ->get();

        // Prepare a collection to store formatted conversations
        $formattedConversations = collect();

        // Array to keep track of seen users
        $seenUsers = [];

        // Loop through conversations and format as required
        $conversations->each(function ($conversation) use ($formattedConversations, &$seenUsers) {
            // Determine the other user in the conversation
            $otherUserId = $conversation->user_one === auth()->id()
                ? $conversation->user_two
                : $conversation->user_one;

            // Check if the other user has already been seen
            if (!in_array($otherUserId, $seenUsers)) {
                // Add the other user to seen users array
                $seenUsers[] = $otherUserId;

                // Calculate unread message count
                $unreadCount = $conversation->messages
                    ->where('user_id', '!=', auth()->id())
                    ->where('is_seen', false)
                    ->count();

                // Determine the other user details
                $otherUser = $conversation->user_one === auth()->id()
                    ? $conversation->userTwo->toArray()
                    : $conversation->userOne->toArray();

                // Format conversation data and push to formatted conversations collection
                $formattedConversations->push([
                    'id' => $conversation->id,
                    'user' => $otherUser,
                    'last_message' => $conversation->messages->last(), // Assuming latest message is last in messages relation
                    'unread_count' => $unreadCount,
                ]);
            }
        });

        // Return the formatted conversations as JSON response
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
