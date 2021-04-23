<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Resources\PostResource;

use App\Models\Post;
use App\Models\Tab;

class PostController extends Controller
{
    public function create(){
        return view('dashboard.post.post_create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|unique:posts|string|regex:/^[a-zA-Z0-9 ]+$/',
            'public' => 'required|boolean',
            'cover_image' => 'required|image',
        ]);
        $schedule_pdf = $request->file('schedule_pdf');
        $cover_image = $request->file('cover_image');
        $coverLocation = $cover_image->storePublicly('posts/covers');

        $post = new Post();
        $post->name = $request->name;
        $post->public = $request->public;
        $post->cover_image = $coverLocation;
        $post->schedule_pdf = '';
        $post->user_id = $request->user()->id;
        $post->save();
        $tab = Tab::create([
            'name' => 'Inicio',
            'is_front_page' => true,
            'content' => null,
            'post_id' => $post->id,
        ]);
        if($schedule_pdf != null){
            // place the file in a folder with the new post id
            // $scheduleLocation = "public/posts/{$post->id}/".$schedule_pdf->getClientOriginalName();
            // Storage::put($scheduleLocation, $schedule_pdf);
            $scheduleLocation = $schedule_pdf->storePublicly("posts/schedules");
            // update the file path on the model
            $post->schedule_pdf = $scheduleLocation;
            $post->save();
        }
        return new PostResource($post);
    }

    public function update(Request $request, Post $post){
        $post->update($request->only('name', 'public'));
        return new PostResource($post);
    }
    
    public function updateFile(Request $request, Post $post){
        $file = $request->file('schedule_pdf');
        if($file != null){
            // delete old file and store the new one
            if($post->schedule_pdf != ''){
                if(Storage::disk('public')->exists($post->schedule_pdf)){
                    Storage::disk('public')->delete($post->schedule_pdf);
                }
            }
            $location = $file->storePublicly('post/schedules');
            $post->update(['schedule_pdf' => $location]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}
