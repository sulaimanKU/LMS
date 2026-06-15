<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    protected $table = 'modules';

    protected $fillable = [
        'teacher_id',
        'workshop_number',
        'title',
        'slug',
        'details',
        'short_description',
        'category',
        'duration',
        'price',
        'status',
        'image',
    ];

    public function enrollments()
    {
        return $this->hasMany(\App\Models\Enrollment::class, 'module_id');
    }

    public function enrolledUsers()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'module_id', 'user_id');
    }

    public function teacher()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_modules', 'module_id', 'teacher_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'module_id');
    }

    public function onlineclasses()
    {
        return $this->hasMany(OnlineClass::class, 'module_id');
    }
}
