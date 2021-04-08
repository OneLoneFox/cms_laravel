<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create(){
        return view('dashboard.post.post_create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|unique|regex:/^[a-zA-Z0-9]+$/',
            'public' => 'required|boolean',
        ]);
        $createdBy = Auth::user()->id;
        return redirect()->route('dashboard.post_create');
    }
}
