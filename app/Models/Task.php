<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    // Child tasks ilişkisi
    public function childTasks()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }
}
