<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;    
    
    protected $fillable = [
        'name', 'email', 'password', 'confirm_code', 'activated',
    ];
    
    protected $hidden = [
        'password', 'remember_token', 'confirm_code',
    ];

    protected $casts = [
        'activated' => 'boolean',
    ];

    protected $dates = ['last_login'];

    public function scopeSocialUser($query, $email)
    {
        return $query->whereEmail($email)->whereNull('password');
    }

    public function isAdmin()
    {
        return ($this->id === 17)? true : false;
    }

    /* Relationships */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
