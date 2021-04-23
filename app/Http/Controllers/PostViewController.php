<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twig;
use App\Helpers\CodexToHtml;
use App\Models\Tab;
use App\Models\Post;

class PostViewController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Post $post, $tabSeoName = null)
    {
        $tab = null;
        if($tabSeoName != null){
            $searchTerm = str_replace('-', ' ', $tabSeoName);
            $tab = Tab::where('name', $searchTerm)
                ->where('post_id', $post->id)
                ->firstOrFail();
        }
        $tabs = Tab::select('id', 'name')->where('post_id', $post->id)->where('is_front_page', false)->get();
        $frontTab = Tab::select('id', 'name')->where('is_front_page', true)->first();
        // if the url only contains the post-seo-name but not a tab then show the default tab
        if($tab == null){
            $currentTab = Tab::where('post_id', $post->id)->where('is_front_page', true)->first();
        }else{
            $currentTab = $tab;
        }
        $parser = $this->getContentParser();
        return view('post.post_view', [
            'post' => $post,
            'front_tab' => $frontTab,
            'tabs' => $tabs,
            'current_tab' => $currentTab,
            'current_tab_content' => json_decode($currentTab->content, true),
            'parser' => $parser,
        ]);
    }

    private function getContentParser(){
        $parser = new CodexToHtml([
            'header' => function($text, $level){
                return "<h${level} class=\"font-bold\">${text}</h${level}>";
            },
            'paragraph' => function($text) {
                return "<p>{$text}</p>";
            },
            'image' => function($file, $caption, $withBorder, $stretched, $withBackground) {
                return Twig::render('/post/components/image.html', [
                    'file' => $file,
                    'caption' => $caption,
                    'with_border' => $withBorder,
                    'stretched' => $stretched,
                    'with_background' => $withBackground
                ]);
            },
            'list' => function($style, $items){
                return Twig::render('/post/components/list.html', [
                    'style' => $style == 'ordered' ? 'ol' : 'li',
                    'items' => $items,
                ]);
            },
            'table' => function($content){
                return Twig::render('/post/components/table.html', [
                    'content' => $content,
                ]);
            },
            'attaches' => function($file, $title){
                return Twig::render('/post/components/attachment.html', [
                    'file' => $file,
                    'title' => $title,
                ]);
            }
        ], false);
        return $parser;
    }
}
