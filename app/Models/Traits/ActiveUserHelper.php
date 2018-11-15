<?php
/**
 * Created by PhpStorm.
 * User: yingwenjie
 * Date: 2018/11/15
 * Time: 10:28 AM
 */

namespace App\Models\Traits;

use App\Models\Reply;
use App\Models\Topic;
use Cache;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Support\Facades\DB;


trait ActiveUserHelper
{

    // 用于存放临时用户数据
    protected $users = [];

    // 配置信息
    protected $topic_weight = 4; // 话题权重
    protected $reply_weight = 1; // 回复权重
    protected $pass_days = 30;    // 多少天内发表过内容
    protected $user_number = 6; // 取出来多少用户

    // 缓存相关配置
    protected $cache_key = 'larabbs_active_users';
    protected $cache_expire_in_minutes = 65;

    public function getActiveUsers()
    {
        // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出活跃用户数据，返回的同时做了缓存。
        return Cache::remember($this->cache_key, $this->cache_expire_in_minutes,function(){
            return $this->calculateActiveUsers();
        });
    }

    public function calculateAndCacheActiveUsers()
    {
        $active_users = $this->calculateActiveUsers();
        $this->cacheActiveUsers($active_users);
    }

    public function cacheActiveUsers($active_users)
    {
        Cache::put($this->cache_key, $active_users, $this->cache_expire_in_minutes);
    }

    public function calculateActiveUsers()
    {
        $this->calculateTopicScore();
        $this->calculateReplyScore();

        // 数组按照得分排序
        $users = array_sort($this->users,function($users){
            return $users['score'];
        });
        // 我们需要的是倒序，高分靠前，第二个参数为保持数组的 KEY 不变
        $users = array_reverse($users,true);

        // 只获取我们想要的数量
        $users = array_slice($users,0,$this->user_number);

        //把用户信息加入数组
        $active_users = collect();
        foreach ($users as $key=>$val){
            $user = $this->find($key);
            if($user){
                $active_users->push($user);
            }
        }

//        dump($active_users);

        return $active_users;
    }

    public function calculateTopicScore()
    {
        // 从话题数据表里取出限定时间范围（$pass_days）内，有发表过话题的用户
        // 并且同时取出用户此段时间内发布话题的数量
        $topic_users = Topic::query()->select(DB::raw('user_id, count(*) as topic_count'))
            ->where('created_at', '>=', Carbon::now()->subDay($this->pass_days))
            ->groupBy('user_id')
            ->get();
        // 根据话题数量计算得分
        foreach ($topic_users as $key=>$val){
            $topic_score = $val->topic_count * $this->topic_weight;
            if(isset($this->users[$val->user_id])){
                $this->users[$val->user_id]['score'] += $topic_score;
            }else{
                $this->users[$val->user_id]['score'] = $topic_score;
            }
        }
    }

    public function calculateReplyScore()
    {
        // 从回复数据表里取出限定时间范围（$pass_days）内，有发表过回复的用户
        // 并且同时取出用户此段时间内发布回复的数量
        $reply_users = Reply::query()->select(DB::raw('user_id,count(*) as reply_count'))
            ->where('created_at', '>=', Carbon::now()->subDay($this->pass_days))
            ->groupBy('user_id')
            ->get();
        // 根据回复数量计算得分
        foreach ($reply_users as $key=>$val){
            $reply_score = $val->reply_count * $this->reply_weight;
            if(isset($this->users[$val->user_id])){
                $this->users[$val->user_id]['score'] += $reply_score;
            }else{
                $this->users[$val->user_id]['score'] = $reply_score;
            }
        }
    }

}