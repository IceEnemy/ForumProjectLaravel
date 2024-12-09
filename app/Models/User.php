<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
// use App\Models\Post;
// use App\Models\Community;
// use App\Models\Comment;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'profile_picture',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // User's own posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Communities the user has joined
    public function communities()
    {
        return $this->belongsToMany(Community::class, 'community_user');
    }

    // Communities the user administers
    public function administratedCommunities()
    {
        return $this->belongsToMany(Community::class, 'community_administrator');
    }

    // Comments made by the user
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Posts the user has upvoted
    public function upvotedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_upvote');
    }

    // Communities the user owns
    public function ownedCommunities()
    {
        return $this->hasMany(Community::class, 'owner_id');
    }
}
