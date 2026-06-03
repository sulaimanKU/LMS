<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';
    public $timestamps = false;

    protected $fillable = ['user_id','name', 'email', 'designation', 'profile_image', 'scopus_link', 'bio', 'specialization', 'linkedin_url', 'twitter_url'];

  public function courses()
    {

       return $this->belongsToMany(Courses::class, 'teacher_modules', 'teacher_id', 'module_id');
    }

    public function user()
{

    return $this->belongsTo(User::class, 'user_id');
}
public function lessons()
{
    return $this->hasMany(Lesson::class, 'teacher_id');
}

public function onlineClasses(){
    return $this->hasMany(OnlineClass::class,'teacher_id');
}
}
