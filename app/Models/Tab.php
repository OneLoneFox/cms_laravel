<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tab extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'post_id',
        'content',
        'is_front_page',
    ];

    protected $appends = [
        'seo_name',
    ];

    public function post(){
        return $this->belongsTo(Post::class);
    }

    public function getSeoNameAttribute(){
        return strtolower( str_replace(' ', '-', $this->name ) );
    }
}
