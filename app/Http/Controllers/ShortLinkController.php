<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\Request;

class ShortLinkController extends Controller
{
    public function index($link) {
        $shortLink = ShortLink::whereLinkCode($link)->first();
        if (!$shortLink) {
            abort(404);
        }
        $shortLink->addViewCount();
        return view('shortLink.index', [
            'shortLink' => $shortLink
        ]);
    }
}
