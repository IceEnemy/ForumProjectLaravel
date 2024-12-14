<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'header_image',
        'description',
        'rules',
        'owner_id',
    ];
    // Relationships
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'community_user');
    }


    public function administrators()
    {
        return $this->belongsToMany(User::class, 'community_administrator');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
