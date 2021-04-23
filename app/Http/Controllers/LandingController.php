<?php

namespace App\Http\Controllers;

use App\Models\Post;

class LandingController extends Controller
{
    public function __invoke(){
        $posts = Post::where('public', true)->latest()->get();
        return view('landing', ['posts' => $posts]);
    }
}