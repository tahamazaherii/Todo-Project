<?php

namespace App\Http\Controllers\api;

use App\Models\Todo;
use App\Models\Category;
use App\Models\CategoryTask;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Services\TodoService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\TodoResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    use ApiResponser;


        protected $todoService;

        public function __construct(TodoService $todoService)
        {
            $this->todoService = $todoService;
        }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = $this->todoService->getAllTodos();
        return $this->successResponse(new TodoResource($todos));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255',
            'due_date' => 'date',
            'categoryName'  => 'nullable|string',
            'categoryId'  => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        if (!$request->categoryName && !$request->categoryId) {
            return response()->json(['message' => 'error you must fill categoryName or categoryId'], 422);
        }
        if ($request->categoryName && $request->categoryId) {
            return response()->json(['message' => 'error you must fill just one input categoryName or categoryId'], 422);
        }


        $data =["user_id" => Auth::user()->id];
        $data += $request->only(['content', 'due_date' ]);
        $todo = $this->todoService->createTodo($data);

        if($request->categoryName){
            $data = [ "name" => $request->categoryName];
            $category = $this->todoService->createCategory($data);

            $data = [ "category_id" => $category->id ,  "todo_id" => $todo->id];
            $categoryTask = $this->todoService->createCategoryTask($data);
        }elseif($request->categoryId){
            $data = [  "category_id" =>$request->categoryId , "todo_id" => $todo->id];
            $categoryTask = $this->todoService->createCategoryTask($data);
        }


        return $this->successResponse(new TodoResource($todo), 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {

        $todo = $this->todoService->getTodoById($todo->id);
        if ($todo) {
            return $this->successResponse(new TodoResource($todo));
        }else{

        }


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {

        //
        $todo = Auth::user()->todos()->find($todo->id);
        $todo->load('categories');
        if (!$todo) {
            return response()->json(['message' => 'تسک یافت نشد.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255',
            'due_date' => 'date',
            'categoryName'  => 'nullable|string',
            'categoryId'  => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }


        if (!$request->categoryName && !$request->categoryId) {
            return response()->json(['message' => 'error you must fill categoryName or categoryId']);
        }
        if ($request->categoryName && $request->categoryId) {
            return response()->json(['message' => 'error you must fill just one input categoryName or categoryId']);
        }

        // به‌روزرسانی todo
        $todo->update([
            'content' => $request->content,
            'due_date' => $request->due_date,
        ]);


        // به‌روزرسانی category_task
        if ($request->categoryId) {
            $todo->categories()->sync($request->categoryId);
        }


        // به‌روزرسانی دسته بندی جدید
        if ($request->categoryName) {
            $category = Category::create([
                'name' => $request->categoryName,
            ]);
            $todo->categories()->sync($category->id);
        }


        return response()->json(['message' => 'تسک با موفقیت به‌روزرسانی شد.', 'todo' => $todo]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $todo = Auth::user()->todos()->find($todo->id);

        if (!$todo) {
            return response()->json(['message' => 'تسک یافت نشد.'], 404);
        }

        // حذف todo
        $todo->delete();

        return response()->json(['message' => 'تسک با موفقیت حذف شد.']);
    }


    //نمایش فعالیت های که کاربر انجام داده شده هست
    public function showFinishStatus()
    {
        $todos = Auth::user()->todos()->with('categories')->where('status', 1)->get();
        return response()->json($todos);
    }


    //برای تیک زدن پخش انجام دادم و برعکس
    public function ChangeStatus(Todo $todo)
    {
        $todo = Auth::user()->todos()->find($todo->id);
        if (!$todo) {
            return response()->json(['message' => 'تسک یافت نشد.'], 404);
        }
        if($todo->status == 0){
            $todo->update([
                'status' => 1,
            ]);
            return response()->json(['message' => 'تسک با موفقیت بروزرسانی شد.', 'todo' => $todo]);
        }else{
            $todo->update([
                'status' => 0,
            ]);
            return response()->json(['message' => 'تسک با موفقیت بروزرسانی شد.', 'todo' => $todo]);
        }
    }

}
