<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = auth()->user()->tickets()->orderByDesc('created_at')->get();
        foreach ($tickets as $ticket) {
            $ticket->localized_date = \App\Services\DateLocalizationService::localize($ticket->created_at);
        }
        $ticketsCount = auth()->user()->tickets()->count();
        return inertia('Dashboard/Support', ['tickets' => $tickets,'ticketsCount' => $ticketsCount]);
    }

    public function getMessagesByTicketId(Request $request)
    {
        $messages = SupportTicket::find($request->input('tickets'))
            ->messages()
            ->with(['sender' => function($query) {
                $query->select('id', 'username');
            }])
            ->get();

        // For each message, get the URL for the first item in the 'support_message_images' media collection
        foreach ($messages as $message) {
            $image = $message->getFirstMedia('support_message_images');
            $message->content_type = ($image) ? 'image' : 'text';
            $message->message = ($image) ? $image->getUrl() : $message->message;
        }

        return response()->json($messages);
    }
}
