<?php
// app/Services/TodoService.php
namespace App\Services;

use App\Repositories\TodoRepository;

class TodoService
{
    protected $todoRepository;

    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function getAllTodos()
    {
        return $this->todoRepository->getAll();
    }

    public function getTodoById($id)
    {
        return $this->todoRepository->find($id);
    }

    public function createTodo(array $data)
    {
        return $this->todoRepository->create($data);
    }

    public function updateTask($id, array $data)
    {
        return $this->todoRepository->update($id, $data);
    }

    public function deleteTask($id)
    {
        return $this->todoRepository->delete($id);
    }
    public function createCategory(array $data)
    {
        return $this->todoRepository->createCategoryName($data);
    }
    public function createCategoryTask(array $data)
    {
        return $this->todoRepository->createCategoryId($data);
    }
}
