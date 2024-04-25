<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UrlController extends Controller
{
    public function rememberURL(Request $request)
    {
        $request->session()->put('remembered_channel_slug', $request->input('slug'));
        return response()->json(['message' => $request->input('slug')]);
    }
}
