<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'message', 'user_id',
    ];

    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }
}
