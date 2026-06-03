<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
   protected $table = 'attendances';
    protected $fillable = ['user_id', 'online_class_id', 'joined_at', 'ip_address'];

    public function onlineClass()
    {
        return $this->belongsTo(OnlineClass::class, 'online_class_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
