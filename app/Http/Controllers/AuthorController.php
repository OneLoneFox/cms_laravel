<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class AuthorController extends Controller
{
    public function index(){
        return view('dashboard.author.author_list', ['posts' => Post::all()]);
    }

    public function create(Request $request, Post $post){
        return view('', ['post' => $post]);
    }

    public function store(){

    }
}
