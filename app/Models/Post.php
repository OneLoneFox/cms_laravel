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
        'start_date',
        'end_date',
    ];
    
    protected $appends = [
        'seo_name',
        'front_tab',
    ];

    public function presentations(){
        return $this->hasMany(Presentation::class);
    }

    public function articles(){
        return $this->hasMany(Article::class);
    }

    /* Unused method <<BROKEN>> */
    public function authors(){
        return $this->belongsToMany(User::class);
    }

    public function participants(){
        return $this->belongsToMany(User::class, 'participant_post')->withPivot('payment_file', 'payment_verified');
    }

    public function tabs(){
        return $this->hasMany(Tab::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getSeoNameAttribute(){
        return strtolower( str_replace(' ', '-', $this->name ) );
    }

    public function getFrontTabAttribute(){
        return Tab::where('post_id', $this->id)->where('is_front_page', true)->first();
    }
}
