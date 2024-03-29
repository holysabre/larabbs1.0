<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function scopeWithOrder($query,$order)
    {
        switch ($order)
        {
            case 'recent':
                //最新发布
                $query->recent();
                break;

            default:
                //默认最后回复
                $query->recentReplied();
                break;
        }
        // 预加载防止 N+1 问题
        return $query->with('category','user');
    }

    //最后回复
    public function scopeRecentReplied($query)
    {
        // 当话题有新回复时，我们将编写逻辑来更新话题模型的 reply_count 属性，
        // 此时会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at','desc');
    }

    //最新发布
    public function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->orderBy('created_at','desc');
    }

    //
    public function link($params = [])
    {
        return route('topics.show',array_merge([$this->id,$this->slug],$params));
    }
}
