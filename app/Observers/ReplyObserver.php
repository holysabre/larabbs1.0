<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;
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
        $topic = $reply->topic;
        //话题的回复数量+1
        $topic->increment('reply_count', 1);
        //保存最后一个回复的用户id
        $topic->last_reply_user_id = Auth::id();
        $topic->save();
        //当回复成功时 通知用户
        $topic->user->notify(new TopicReplied($reply));
    }
}