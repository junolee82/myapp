<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'commentable_type',
        'commentable_id',
        'user_id',
        'parent_id',
        'content',
    ];

    protected $hidden = [
        'user_id',
        'commentable_type',
        'commentable_id',
        'parent_id',
        'deleted_at',
    ];

    protected $with = [
        'user',
        'votes',
    ];    

    protected $appends = [
        'up_count',
        'down_count',
    ];

    protected $dates = [
        'deleted_at',
    ];

    /* Accessors */
    public function getUpCountAttribute()
    {
        return (int) $this->votes()->sum('up');
    }

    public function getDownCountAttribute()
    {
        return (int) $this->votes()->sum('down');
    }

    /* Relationships */    
    // N : 1
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // N : N 다형적 관계
    public function commentable()
    {
        return $this->morphTo();
    }

    // 댓글끼리 재귀적인 1 : N
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->latest();
    }

    // 최상위 댓글
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id', 'id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
