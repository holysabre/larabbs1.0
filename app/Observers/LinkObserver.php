<?php
/**
 * Created by PhpStorm.
 * User: yingwenjie
 * Date: 2018/11/15
 * Time: 5:18 PM
 */

namespace App\Observers;

use Cache;
use App\Models\Link;

class LinkObserver
{

    public function saved(Link $link)
    {
        //保存时，清空缓存
        Cache::forget($link->cache_key);
    }

}