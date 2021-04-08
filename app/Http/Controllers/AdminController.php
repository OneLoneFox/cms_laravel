<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function index(){
        $admins = User::where('user_type', User::ADMIN)->get();
        return view('dashboard.admin.admin_list', ['admins' => $admins->toJson()]);
    }
}
