<?php
/**
 * Created by PhpStorm.
 * User: yingwenjie
 * Date: 2018/11/15
 * Time: 5:11 PM
 */
use App\Models\Link;

return [
    'title'   => '资源推荐',
    'single'  => '资源推荐',

    'model'   => Link::class,

    // 访问权限判断
    'permission'=> function()
    {
        // 只允许站长管理资源推荐链接
        return Auth::user()->hasRole('Founder');
    },

    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'title' => [
            'title'    => '名称',
            'sortable' => false,
        ],
        'url' => [
            'title'    => '链接',
            'sortable' => false,
        ],
        'operation' => [
            'title'  => '管理',
            'sortable' => false,
        ],
    ],
    'edit_fields' => [
        'title' => [
            'title'    => '名称',
        ],
        'url' => [
            'title'    => '链接',
        ],
    ],
    'filters' => [
        'id' => [
            'title' => '标签 ID',
        ],
        'title' => [
            'title' => '名称',
        ],
    ],
];