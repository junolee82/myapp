<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
    
    protected $fillable = [
        'name', 'email', 'password', 'confirm_code', 'activated',
    ];
    
    protected $hidden = [
        'password', 'remember_token', 'confirm_code'
    ];

    protected $casts = [
        'activated' => 'boolean',
    ];

    protected $dates = ['last_login'];
    
}
