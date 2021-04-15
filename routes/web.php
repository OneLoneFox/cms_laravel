<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\AdminController;
// use App\Http\Controllers\PostController;
use App\Http\Controllers\PostPreviewController;
use App\Models\Post;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', LandingController::class)->name('home');

Route::get('/{postSeoName}/register', function($postSeoName){
    return 'WIP';
})->name('post_register');

Route::prefix('dashboard')
->name('dashboard.')
->middleware(['auth'])
->group(function(){
    Route::get('/', function () {
        return view('dashboard.index');
    })->name('home');

    Route::get('/admins', [AdminController::class, 'index'])->name('admins');

    Route::get('/authors', [AuthorController::class, 'index'])->name('authors');

    Route::get('/participants', [ParticipantController::class, 'index'])->name('participants');

    Route::prefix('posts')
    ->group(function(){
        Route::get('/', function(){
            return view('dashboard.post.post_list', ['posts' => Post::all()]);
        })->name('post_list');
        
        // Route::get('/create', [PostController::class, 'create'])->name('post_create');
        // Route::post('/create', [PostController::class, 'store']);
        
        Route::get('/{post}/edit', function(Post $post){
            return view('dashboard.post.post_edit', ['post' => $post]);
        })->name('post_edit');
        
        Route::get('/{post}/delete', function(Post $post){
            $post->delete();
            return redirect()->route('dashboard.post_list');
        })->name('post_delete');

        Route::get('preview/{postId}/{tabId?}', PostPreviewController::class)->name('post_preview');
    });
});

require __DIR__.'/auth.php';
