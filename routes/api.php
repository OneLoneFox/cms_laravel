<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Http\Resources\AuthorResource;
use App\Http\Resources\AuthorCollection;
use App\Http\Resources\TabResource;
use App\Http\Resources\TabCollection;
use App\Models\Author;
use App\Models\Post;
use App\Models\Tab;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/authors', function (){
    return new AuthorCollection(Author::orderBy('id', 'desc')->paginate(10));
});

Route::post('/posts', function (Request $request){
    $request->validate([
        'name' => 'required|string|regex:/^[a-zA-Z0-9]+$/',
        'public' => 'required|boolean',
        'schedule_pdf' => 'file',
    ]);
    $file = $request->file('schedule_pdf');
    var_dump($request->user());die();
    // var_dump($request->name);
    // var_dump($request->public);
    // var_dump($request->file('schedule_pdf'));
    // die();
    $post = new Post();
    $post->name = $request->name;
    $post->public = $request->public;
    $post->schedule_pdf = '';
    $post->user_id = Auth::id();
    $post->save();
    // place the file in a folder with the new post id
    $location = "public/posts/{$post->id}/".$file->originalName;
    Storage::put($location, file);
    // update the file path on the model
    $post->schedule_pdf = $location;
    $post->save();
    return new PostResource($post);
});

Route::get('/posts/{postId}/authors/', function (Request $request, $postId){
    $orderBy = $request->get('orderBy') ?? 'users.name';
    if($orderBy == 'id'){
        $orderBy = 'authors.id';
    }
    $orderDirection = $request->get('direction') ?? 'asc';
    $postAuthors = Author::where('post_id', $postId)
                            ->join('users', 'users.id', '=', 'authors.user_id')
                            ->orderBy($orderBy, $orderDirection)
                            ->paginate(10);
    return new AuthorCollection($postAuthors);
});

Route::get('/posts/{postId}/tabs', function ($postId){
    $tabs = Tab
        ::select('id', 'name', 'post_id', 'is_front_page', 'created_at', 'updated_at')
        ->where('post_id', $postId)
        ->get();
    return new TabCollection($tabs);
});

Route::post('/tabs', function(Request $request){
    $request->validate([
        'name' => 'required|string|regex:/^[a-zA-Z0-9]+$/',
        'post_id' => 'exists:posts,id',
    ]);
    $tab = Tab::create([
        'name' => $request->name,
        'post_id' => $request->post_id,
        'content' => $request->content,
        'is_front_page' => false,
    ]);
    return new TabResource($tab);
});

Route::get('/tabs/{tab}', function(Tab $tab){
    return new TabResource($tab);
});

Route::post('/tabs/{tab}/delete', function(Tab $tab){
    $tab->delete();

    return response(null, Response::HTTP_NO_CONTENT);
});

Route::patch('/tabs/{tab}', function(Request $request, Tab $tab){
    $request->validate([
        'content' => 'json',
    ]);
    $_PATCH = json_decode(file_get_contents('php://input'), true);
    $tab->content = json_encode($_PATCH['content']);
    $tab->save();
    return new TabResource($tab);
});