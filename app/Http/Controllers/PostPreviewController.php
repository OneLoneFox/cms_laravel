<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twig;
use App\Helpers\CodexToHtml;
use App\Models\Tab;
use App\Models\Post;

class PostPreviewController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $postId, $tabId = null)
    {
        $post = Post::findOrFail($postId);
        $tabs = Tab::where('post_id', $postId)->where('is_front_page', false)->get();
        // $tabs = Tab::where('post_id', $postId)->get();
        $frontTab = Tab::select('id', 'name')->where('is_front_page', true)->first();
        if($tabId == null){
            $currentTab = Tab::where('post_id', $postId)->where('is_front_page', true)->first();
        }else{
            $currentTab = Tab::findOrFail($tabId);
        }
        $parser = $this->getContentParser();
        return view('dashboard.post.post_preview', [
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
                return "<h${level}>${text}</h${level}>";
            },
            'paragraph' => function($text) {
                return "<p>{$text}</p>";
            },
            'image' => function($file, $caption, $widthBorder, $stretched, $withBackground) {
                return Twig::render('/post/components/image.html', [
                    'file' => $file,
                    'caption' => $caption,
                    'with_border' => $widthBorder,
                    'stretched' => $stretched,
                    'with_background' => $withBackground
                ]);
                // return "<img src=\"{$file['url']}\" title=\"{$caption}\" alt=\"{$caption}\">";
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
        ], false);
        return $parser;
    }
}
