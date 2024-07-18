<?php
// app/Repositories/TodoRepository.php
namespace App\Repositories;

use App\Models\Todo;
use App\Models\Category;
use App\Models\CategoryTask;
use Illuminate\Support\Facades\Auth;

class TodoRepository
{
    public function getAll()
    {
        return Auth::user()->todos()->with('categories')->where('status', 0)->get();
        // return response()->json($todos);
        // return Todo::all();
    }

    public function find($id)
    {
        return Todo::findOrFail($id);
    }

    public function create(array $data)
    {
        // $todoData = ["user_id" => Auth::user()->id,"content" => $data['content'] ,"due_date" => $data['due_date'] ];
        // $todo =Todo::create($todoData);
        // if($data['categoryName']){
        //     $category =  Category::create($data['categoryName']);
        //          //create CategoryTask
        //     CategoryTask::create([
        //     'todo_id' =>  $todo->id,
        //     'category_id' => $category->id,
        //     ]);
        // }
        // if($data['categoryId']){
        //     CategoryTask::create([
        //     'todo_id' =>  $todo->id,
        //     'category_id' => $data['categoryId'],
        //     ]);
        // }


        // return  $todo;
        return  Todo::create($data);;
    }

    public function update($id, array $data)
    {
        $Todo = Todo::findOrFail($id);
        $Todo->update($data);
        return $Todo;
    }

    public function delete($id)
    {
        $Todo = Todo::findOrFail($id);
        $Todo->delete();
        return $Todo;
    }
    public function createCategoryName(array $data)
    {
       
        return  Category::create($data);;
    }
    public function createCategoryId(array $data)
    {
        return  CategoryTask::create($data);;
    }
}
