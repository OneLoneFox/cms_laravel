<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class ParticipantController extends Controller
{
    public function index(){
        return view('dashboard.participant.participant_list', ['posts' => Post::all()]);
    }
}
