<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class AuthorController extends Controller
{
    public function index(){
        return view('dashboard.author.author_list', ['posts' => Post::all()]);
    }
}
