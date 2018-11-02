<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/1
 * Time: 3:53 PM
 */

namespace Chang\Erp\Observers;

use Chang\Erp\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentObserver
{
    public function creating(Comment $comment)
    {
        $comment->user_id = Auth::id();
    }
}