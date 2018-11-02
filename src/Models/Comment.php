<?php

namespace Chang\Erp\Models;


use Chang\Erp\Observers\CommentObserver;

class Comment extends Model
{
    protected $fillable = [
        'body',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();
        self::observe(CommentObserver::class);
    }


    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
