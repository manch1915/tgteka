<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        $supportChats = SupportTicket::all();

        return response()->json($supportChats);
    }

    public function store(Request $request)
    {
    }

    public function show(SupportMessage $supportMessage)
    {
    }

    public function update(Request $request, SupportMessage $supportMessage)
    {
    }

    public function destroy(SupportTicket $support)
    {
        $support->delete();
        return response()->json();
    }

}
