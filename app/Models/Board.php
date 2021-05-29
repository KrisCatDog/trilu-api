<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function members()
    {
        return $this->belongsToMany(User::class, 'board_members');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function boardLists()
    {
        return $this->hasMany(BoardList::class)->orderBy('order');
    }
}
