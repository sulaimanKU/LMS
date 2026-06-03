<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table = 'lessons';
   protected $fillable = [ 'module_id', 'teacher_id','title', 'slug', 'short_text', 'full_content', 'documnet_path', 'order_number', 'is_preview'];


  public function module() {

        return $this->belongsTo(Courses::class, 'module_id');
    }
    public function resources()
    {
        return $this->hasMany(LessonResource::class,'lesson_id');
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
