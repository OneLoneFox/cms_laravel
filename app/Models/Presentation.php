<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'presentation_date',
        'start_time',
        'end_time',
        'location',
        'post_id',
    ];

    public function author(){
        return $this->belongsTo(Author::class);
    }

    public function post(){
        return $this->belongsTo(Post::class);
    }
}
