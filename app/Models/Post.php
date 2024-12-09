<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'community_id',
        'user_id',
        'title',
        'content',
        'image',
    ];
    // Relationships
    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function upvotes()
    {
        return $this->belongsToMany(User::class, 'post_upvote');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
