<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ADMIN = 0;
    const PARTICIPANT = 1;
    const AUTHOR = 2;

    const USER_TYPE_CHOICES = [
        self::ADMIN,
        self::PARTICIPANT,
        self::AUTHOR
    ];

    // publicly valid user types
    const PUBLIC_USER_TYPE_CHOICES = [
        self::PARTICIPANT,
        self::AUTHOR,
    ];

    const MALE = 'm';
    const FEMALE = 'f';
    const NONE = 'n';

    const USER_SEX_CHOICES = [
        self::MALE,
        self::FEMALE,
        self::NONE,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cellphone',
        'sex',
        'user_type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function articles(){
        return $this->hasMany(Article::class);
    }

    public function posts(){
        return $this->belongsToMany(Post::class, 'participant_post')->withPivot('payment_file', 'payment_verified');
    }

    public function workshops(){
        return $this->belongsToMany(Workshop::class);
    }

}
