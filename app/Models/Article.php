<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    const PENDING = 0;
    const OBSERVATIONS = 1;
    const ACCEPTED = 2;
    const REJECTED = 3;

    const STATUS_CHOICES = [
        self::PENDING,
        self::OBSERVATIONS,
        self::ACCEPTED,
        self::REJECTED,
    ];

    const STATUS_MAP_ES = [
        self::PENDING => 'Pendiente',
        self::OBSERVATIONS => 'Onservaciones, requiere revisión.',
        self::ACCEPTED => 'Aceptado',
        self::REJECTED => 'Rechazado',
    ];

    protected $fillable = [
        'title',
        'article_pdf',
        'payment_file',
        'payment_verified',
        'status',
        'user_id',
        'post_id',
    ];

    public function author(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function post(){
        return $this->belongsTo(Post::class);
    }
}
