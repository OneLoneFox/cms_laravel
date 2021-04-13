<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TempImages;

class TabImageUploadController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
        ]);
        $user_id = $request->user()->id;
        $image = $request->file('image');
        $location = $image->storePublicly("posts/{$request->post_id}/{$request->tab_id}/images");
        $public_path = asset('storage/'.$location);
        TempImages::create([
            'path' => $location,
            'post_id' => $request->post_id,
            'tab_id' => $request->tab_id,
            'user_id' => $user_id,
        ]);
        return response()->json([
            'success' => 1,
            'file'=> [
                'url' => $public_path,
            ],
        ]);
    }
}
