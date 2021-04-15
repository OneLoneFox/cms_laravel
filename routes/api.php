<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Http\Resources\AuthorResource;
use App\Http\Resources\AuthorCollection;
use App\Http\Resources\TabResource;
use App\Http\Resources\TabCollection;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;

use App\Models\Author;
use App\Models\Post;
use App\Models\Tab;

use App\Http\Controllers\TabImageUploadController;
use App\Http\Controllers\TabAttachmentUploadController;
use App\Http\Controllers\TabUpdateController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function (){
    
    Route::get('/authors', function (){
        return new AuthorCollection(Author::orderBy('id', 'desc')->paginate(10));
    });
    
    Route::prefix('/posts')->group(function (){
        Route::get('/{postId}/tabs', function ($postId){
            $tabs = Tab
                ::select('id', 'name', 'post_id', 'is_front_page', 'created_at', 'updated_at')
                ->where('post_id', $postId)
                ->get();
            return new TabCollection($tabs);
        });

        Route::get('/', function (){
            return new PostCollection(Post::all());
        });
        
        Route::post('/', function (Request $request){
            $request->validate([
                'name' => 'required|unique:posts|string|regex:/^[a-zA-Z0-9 ]+$/',
                'public' => 'required|boolean',
            ]);
            $file = $request->file('schedule_pdf');
            $post = new Post();
            $post->name = $request->name;
            $post->public = $request->public;
            $post->schedule_pdf = '';
            $post->user_id = $request->user()->id;
            $post->save();
            $tab = Tab::create([
                'name' => 'Inicio',
                'is_front_page' => true,
                'content' => null,
                'post_id' => $post->id,
            ]);
            if($file != null){
                // place the file in a folder with the new post id
                // $location = "public/posts/{$post->id}/".$file->getClientOriginalName();
                // Storage::put($location, $file);
                $location = $file->storePublicly("posts/schedules");
                // update the file path on the model
                $post->schedule_pdf = $location;
                $post->save();
            }
            return new PostResource($post);
        });
        
        Route::get('/{postId}/authors/', function (Request $request, $postId){
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
    });

    Route::prefix('/tabs')->group(function(){
        Route::post('/', function(Request $request){
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'regex:/^[a-zA-Z0-9]+$/',
                    Rule::unique('tabs')->where(function($query) use ($request){
                        return $query->where('post_id', $request->post_id);
                    }),
                    // 'required|string|regex:/^[a-zA-Z0-9]+$/',
                ],
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
        
        Route::get('/{tab}', function(Tab $tab){
            // ToDo: get all temp_images record where post_id and user_id match the current ones
            // delete all the unused files stored there and delete those records

            return new TabResource($tab);
        });
        
        Route::patch('/{tab}', TabUpdateController::class);
        
        Route::post('/{tab}/delete', function(Tab $tab){
            $tab->delete();
        
            return response(null, Response::HTTP_NO_CONTENT);
        });

        Route::post('/images', TabImageUploadController::class);
        Route::post('/attachments', TabAttachmentUploadController::class);
    });
});