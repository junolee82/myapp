<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'content',
        'notification',
        'view_count',
        'notification',
    ];

    protected $with = ['user'];

    protected $hidden = [
        
    ];

    /* Accessor 
     * 글 목록에 댓글 수 출력 접근자
     */
    public function getCommentCountAttribute()
    {
        return (int) $this->comments->count();
    }

    /* Relationships */
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
