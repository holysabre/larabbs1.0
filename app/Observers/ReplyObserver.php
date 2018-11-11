<?php

namespace App\Observers;

use App\Models\Reply;
use Auth;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        //过滤内容 防止被攻击
        $reply->content = clean($reply->content, 'user_reply_content');
    }

    public function created(Reply $reply)
    {
        //话题的回复数量+1
        $reply->topic->increment('reply_count', 1);
        $reply->topic->last_reply_user_id = Auth::id();
    }
}