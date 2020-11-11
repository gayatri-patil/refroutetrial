<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'contactno',
        'email',
        'password',
    ];

    protected $appends = ['avatar'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function getAvatar(){
        return 'https://gravatar.com/avatar/'.md5($this->gmail).'/?s=45&d=mm';
    }

    public function getAvatarAttribute() {
        return $this->getAvatar();
    }

    public function getRouteKeyName() {
        return 'name';
    }

    public function isNotTheUser(User $user) {
        return $this->id !== $user->id;
    }

    public function isFollowing(User $user) {
        return (bool)$this->following->where('id', $user->id)->count();
    }

    public function canFollow(User $user) {
        #dd($user);
        if (!$this->isNotTheUser($user)) {
            return false;
        }
        return !$this->isFollowing($user);
    }

    public function canUnfollow(User $user) {
        return $this->isFollowing($user);
    }

    public function following() {
        return $this->belongsToMany('App\Models\User', 'follows', 'user_id', 'follower_id');
    }

    public function followers() {
        return $this->belongsToMany('App\Models\User', 'follows', 'follower_id', 'user_id');
    }
}
