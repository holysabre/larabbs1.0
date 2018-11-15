<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Request $request, Category $category, Topic $topic, User $user)
    {
        $topics = $topic->withOrder('order', $request->order)
            ->where('category_id', $category->id)
            ->paginate(10);
        $active_users = $user->getActiveUsers();
        return view('topics.index',compact('topics','category','active_users'));
    }
}
