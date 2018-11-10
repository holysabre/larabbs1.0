<?php

namespace App\Observers;

use App\Models\Topic;
use App\Handlers\TranslateHandler;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function saving(Topic $topic)
    {
        //过滤内容 防止被攻击
        $topic->body = clean($topic->body, 'user_topic_body');
        //截取摘要
        $topic->excerpt = make_excerpt($topic->body);
        //翻译标题
        if(!$topic->slug){
            $topic->slug = app(TranslateHandler::class)->translate($topic->title);
        }
    }
}