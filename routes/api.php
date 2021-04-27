<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Http\Resources\ArticleResource;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\AuthorCollection;
use App\Http\Resources\TabResource;
use App\Http\Resources\TabCollection;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostCollection;
use App\Http\Resources\ParticipantResource;
use App\Http\Resources\ParticipantCollection;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;

use App\Models\User;
use App\Models\Author;
use App\Models\Article;
use App\Models\Post;
use App\Models\Tab;

use App\Notifications\ParticipantPaid;

use App\Http\Controllers\TabImageUploadController;
use App\Http\Controllers\TabAttachmentUploadController;
use App\Http\Controllers\TabUpdateController;
use App\Http\Controllers\PostController;

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
    
    // Route::get('/authors', function (){
    //     return new AuthorCollection(Author::orderBy('id', 'desc')->paginate(10));
    // });
    
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
        
        Route::post('/', [PostController::class, 'store']);

        Route::patch('/{post}', [PostController::class, 'update']);

        Route::post('/{post}/schedule', [PostController::class, 'updateFile']);
        
        Route::get('/{postId}/authors/', function (Request $request, $postId){
            $orderBy = $request->get('orderBy') ?? 'name';
            $orderDirection = $request->get('direction') ?? 'asc';
            $postAuthors = User::select('users.*', 'articles.id as article')
                                ->where('user_type', User::AUTHOR)
                                ->where('posts.id', $postId)
                                ->join('articles', 'articles.user_id', '=', 'users.id')
                                ->join('posts', 'posts.id', '=', 'articles.post_id')
                                ->orderBy($orderBy, $orderDirection)
                                ->paginate(10);
            return new AuthorCollection($postAuthors);
        });

        Route::get('/{post}/participants/', function (Request $request, Post $post){
            $orderBy = $request->get('orderBy') ?? 'name';
            $orderDirection = $request->get('direction') ?? 'asc';
            $participants = $post->participants()->orderBy($orderBy, $orderDirection)->paginate(10);
            return new ParticipantCollection($participants);
        });
        Route::patch('/{post}/participants/{participant}/', function (Request $request, Post $post, User $participant){
            $post->participants()->updateExistingPivot(
                $participant->id, $request->only('payment_verified')
            );
            // send email if payment gets verified
            if($request->payment_verified == true){
                $participant->notify(new ParticipantPaid($participant, $post));
            }
            return new ParticipantResource($participant);
        });
    });

    Route::prefix('/authors')->group(function (){
        // Route::patch('/{authorId}/articles/{article}', function(Request $request, $authorId, Article $article){
        //     $article->update($request->only('status', 'payment_verified'));
        //     // fetch author after update to return fresh data
        //     $author = Author::find($authorId);
        //     // it do be lookin kinda fresh ngl
        //     return new AuthorResource($author);
        // });
    });

    Route::prefix('/articles')->group(function (){
        Route::patch('/{article}', function(Request $request, Article $article){
            $article->update($request->only('status', 'payment_verified'));
            return new ArticleResource($article);
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