<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'host',
        'location',
        'limit',
        'post_id',
        'date',
        'start_time',
        'end_time',
    ];

    // public function participants(){
    //     return $this->belongsToMany(Participant::class);
    // }

    public function participants(){
        return $this->belongsToMany(User::class);
    }
}
