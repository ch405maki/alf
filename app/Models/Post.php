<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'category',
        'title',
        'content',
        'document',
        'link',
        'image',
        'status',
    ];

        
    protected $casts = [
        'image' => 'array',
    ];

    public function setImageAttribute($value)
    {
        $this->attributes['image'] = $value ? json_encode($value) : null;
    }

    public function getImageAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}

