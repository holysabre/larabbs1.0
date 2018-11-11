<?php

namespace App\Observers;

use App\Jobs\TranslateSlug;
use App\Models\Topic;
use App\Handlers\TranslateHandler;
use Illuminate\Support\Facades\DB;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{

    public function saving(Topic $topic)
    {
        //过滤内容 防止被攻击
        $topic->body = clean($topic->body, 'user_topic_body');
        //截取摘要
        $topic->excerpt = make_excerpt($topic->body);
    }

    public function saved(Topic $topic)
    {
        //翻译标题
        if(!$topic->slug){
//            $topic->slug = app(TranslateHandler::class)->translate($topic->title);
            dispatch(new TranslateSlug($topic));
        }
    }

    public function deleted(Topic $topic)
    {
        //当删除话题时，同时删除该话题下所有的回复
        DB::table('replies')->where('topic_id', $topic->id)->delete();
    }
}