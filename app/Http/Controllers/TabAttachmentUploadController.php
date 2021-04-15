<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TabAttachmentUploadController extends Controller
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
            'file' => 'required|file',
        ]);
        $file = $request->file('file');
        $location = $file->storePublicly('posts/attachments');
        $public_path = asset('storage/'.$location);
        return response()->json([
            'success' => 1,
            'file' => [
                'url' => $public_path,
                'name' => $file->getClientOriginalName(),
                'extension' => $file->extension(),
                'size' => $file->getSize(),
            ],
        ]);
    }
}
