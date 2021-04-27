<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Article;
use App\Models\Post;
use App\Models\Tab;
use App\Models\User;

class PostRegisterController extends Controller
{
    public function create(Request $request, Post $post){
        $tabs = Tab::select('id', 'name')->where('post_id', $post->id)->where('is_front_page', false)->get();
        $frontTab = Tab::select('id', 'name')->where('is_front_page', true)->first();
        $user = Auth::user();
        if($user->user_type == User::PARTICIPANT){
            $isRegistered = $post->participants->contains($user->id);
            $participantInPost = $post->participants()
                ->wherePivot('user_id', $user->id)
                ->first();
            if($participantInPost){
                $paymentVerified = $participantInPost->pivot->payment_verified;
            }else{
                $paymentVerified = false;
            }
            
        }else{
            $article = Article::where('user_id', $user->id)->where('post_id', $post->id)->first();
            $paymentVerified = $article->payment_verified ?? false;
            $isRegistered = $article !== null;
            $paymentUploaded = $article->payment_file ?? null ? true : false;
        }
        return view('post.post_register', [
            'post' => $post,
            'front_tab' => $frontTab,
            'tabs' => $tabs,
            'is_registered' => $isRegistered,
            'payment_verified' => $paymentVerified,
            'payment_uploaded' => $paymentUploaded ?? null,
            'article' => $article ?? null,
        ]);
    }

    public function store(Request $request, Post $post){
        $user = Auth::user();
        if($user->user_type == User::PARTICIPANT){
            // handle participant registration
            $this->signParticipant($request, $post, $user);
        }else{
            // handle author registration
            $this->signAuthor($request, $post, $user);
        }
        return redirect()->route('post_register', $post->seo_name);
    }
    
    private function signParticipant(Request $request, Post $post, User $participant){
        // prevent multiple records from being inserted
        if($post->participants->contains($participant->id)){return;}
        $request->validate([
            'payment_file' => 'required|file|mimetypes:image/jpeg,image/png,application/pdf',
        ]);
        // store the payment file for verification
        $file = $request->file('payment_file');
        $fileLocation = $file->storePublicly("posts/{$post->id}/payments");
        // sign the user to that post, payment_verified must be updated at dashboard
        $post->participants()->attach($participant->id, [
            'payment_file' => $fileLocation,
            'payment_verified' => false,
        ]);
    }

    private function signAuthor(Request $request, Post $post, User $author){
        $article = Article::where('post_id', $post->id)->where('user_id', $author->id)->first();
        if($article != null){
            // author article is already registered
            if($article->payment_file){
                // author has uploaded payment file for verification CANCEL POST OPERATION
                return;
            }
            $request->validate([
                'payment_file' => 'required|file|mimetypes:image/jpeg,image/png,application/pdf',
            ]);
            $file = $request->file('payment_file');
            $fileLocation = $file->storePublicly("posts/{$post->id}/payments");
            $article->update([
                'payment_file' => $fileLocation,
            ]);
            return;
        }
        $request->validate([
            'title' => 'required|string',
            'article_pdf' => 'required|file|mimetypes:application/pdf',
        ]);
        $file = $request->file('article_pdf');
        $fileLocation = $file->storePublicly("posts/{$post->id}/articles");
        $article = Article::create([
            'title' => $request->title,
            'article_pdf' => $fileLocation,
            'status' => Article::PENDING,
            'payment_verified' => false,
            'post_id' => $post->id,
            'user_id' => $author->id,
        ]);
    }
}
