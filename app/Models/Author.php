<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    const STATUS_ACCEPTED = 0;
    const STATUS_PENDING = 1;
    const STATUS_REJECTED = 2;

    const STATUS_CHOICES = [
        self::STATUS_ACCEPTED => 'aceptado',
        self::STATUS_PENDING => 'pendiente',
        self::STATUS_REJECTED => 'rechazado',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function presentation(){
        return $this->belongsTo(Presentation::class);
    }

    public function coAuthors(){
        return $this->hasMany(CoAuthor::class);
    }
}
