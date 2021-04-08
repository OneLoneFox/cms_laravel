<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'public',
        'schedule_pdf',
        'user_id',
    ];

    public function authors(){
        return $this->hasMany(Author::class);
    }

    public function tabs(){
        return $this->hasMany(Tab::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
