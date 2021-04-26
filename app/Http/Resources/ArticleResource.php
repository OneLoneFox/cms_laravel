<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $default = parent::toArray($request);
        $overrides = [
            'payment_verified' => (bool)$this->payment_verified,
            'article_pdf' => $this->when(!$this->article_pdf, function(){
                return asset('storage/'.$this->article_pdf);
            }),
        ];
        return array_merge($default, $overrides);
    }
}
