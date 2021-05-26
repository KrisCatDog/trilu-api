<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function loginToken()
    {
        return $this->hasOne(LoginToken::class);
    }

    public function joinedBoards()
    {
        return $this->belongsToMany(Board::class, 'board_members');
    }

    public function boards()
    {
        return $this->hasMany(Board::class, 'creator_id');
    }
}
