<?php

namespace App\Models;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Todo extends Model
{
    use HasFactory;

    protected $guarded = [];


    // relation of user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relation of category
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_tasks', 'todo_id', 'category_id');
    }
}
