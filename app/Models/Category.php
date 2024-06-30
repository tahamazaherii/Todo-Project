<?php

namespace App\Models;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];
    // relation of user
    public function todos()
    {
        return $this->belongsToMany(Todo::class, 'category_tasks', 'category_id', 'todo_id');
    }
}
