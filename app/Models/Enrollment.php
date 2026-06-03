<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = 'enrollments';
    protected $fillable = ['user_id', 'module_id', 'status'];

    public function modules()
    {
        return $this->belongsTo(Courses::class,'module_id');

    }
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}


