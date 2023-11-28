<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = auth()->user()->tickets;
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
                $query->select('id', 'profile_photo_url');
            }])
            ->get();

        return response()->json($messages);
    }
}
