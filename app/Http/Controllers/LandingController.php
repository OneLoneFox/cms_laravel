<?php

namespace App\Http\Controllers;

use App\Models\Post;

class LandingController extends Controller
{
    public function __invoke(){
        $posts = Post::all();
        return view('landing', ['posts' => $posts]);
    }
}