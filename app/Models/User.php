<?php

namespace App\Models;

use App\Models\Traits\RecordLastActiveHelper;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reply[] $replies
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Topic[] $topics
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User role($roles)
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $avatar
 * @property string|null $introduction
 * @property int $notification_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIntroduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereNotificationCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 */
class User extends Authenticatable
{
    use Notifiable{
        notify as protected laravelNotify;
    }
    use HasRoles;
    use Traits\ActiveUserHelper;
    use Traits\RecordLastActiveHelper;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar','introduction',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //关联模型 一对多
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    //通知
    public function notify($instance)
    {
        //回复用户为自身时，不通知
        if($this->id == Auth::id()){
            return;
        }
        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

    //清空通知
    public function martAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications()->martAsRead();
    }

    //修改器 修改密码时 给密码加密
    public function setPasswordAttribute($value)
    {
        //当密码的长度没有60的时候，认为没有进行加密
        if(strlen($value) != 60){
            $value = bcrypt($value);
        }
        $this->attributes['password'] = $value;
    }

    //修改头像 补全地址
    public function setAvatarAttribute($value)
    {
        //如果头像不是以http开头的话 则认为需要补全图片路径
        if(!starts_with($value,'http')){
            $value = config('app.url').'/uploads/images/avatars/'.$value;
        }
        $this->attributes['avatar'] = $value;
    }
}
