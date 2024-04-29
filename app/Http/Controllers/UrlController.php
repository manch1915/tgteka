<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UrlController extends Controller
{
    public function rememberURL(Request $request)
    {
        $slug = $request->input('slug');

        if(empty($slug)) {
            $request->session()->forget('remembered_channel_slug');
            return response()->json(['message' => 'Slug is empty, session forgotten']);
        }

        $request->session()->put('remembered_channel_slug', $slug);
        return response()->json(['message' => $slug]);
    }
}
