<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Article;

class AuthorResource extends JsonResource
{
    public $preserveKeys = true;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $default = parent::toArray($request);
        $article = Article::findOrFail($this->article);
        $overrides = [
            'article' => new ArticleResource($article),
        ];
        return array_merge($default, $overrides);
    }
}
