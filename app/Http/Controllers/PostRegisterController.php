<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Tab;

class PostRegisterController extends Controller
{
    public function index(Request $request, Post $post){
        $tabs = Tab::select('id', 'name')->where('post_id', $post->id)->where('is_front_page', false)->get();
        $frontTab = Tab::select('id', 'name')->where('is_front_page', true)->first();
        return view('post.post_register', [
            'post' => $post,
            'front_tab' => $frontTab,
            'tabs' => $tabs,
        ]);
    }
}
