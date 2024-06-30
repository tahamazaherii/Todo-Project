<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Todo;
use App\Models\Category;
use App\Models\CategoryTask;


class TodoTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    //ایجاد تسک به همراه اسم دسته بندی
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_creates_a_todo_with_category_name()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/todos', [
            'content' => 'Test todo',
            'due_date' => '2024-06-30',
            'categoryName' => 'Test Category'
        ]);


        $response->assertStatus(200);

        $this->assertDatabaseHas('todos', [
            'content' => 'Test todo',
            'due_date' => '2024-06-30',
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category'
        ]);

        $todo = Todo::first();
        $category = Category::first();

        $this->assertDatabaseHas('category_tasks', [
            'todo_id' => $todo->id,
            'category_id' => $category->id,
        ]);
    }

    //ایجاد تسک به همراه ای دی دسته بندی
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_creates_a_todo_with_existing_category_id()
    {
        $this->actingAs($this->user);

        $category = Category::factory()->create();

        $response = $this->postJson('/api/todos', [
            'content' => 'Test todo',
            'due_date' => '2024-06-30',
            'categoryId' => $category->id
        ]);

        $response->assertStatus(200);


        $this->assertDatabaseHas('todos', [
            'content' => 'Test todo',
            'due_date' => '2024-06-30',
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('category_tasks', [
            'todo_id' => Todo::first()->id,
            'category_id' => $category->id,
        ]);
    }

    //بروزرسانی تسک با نام دسته بندی
    /** @test */
    public function it_can_update_a_todo()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/todos', [
            'content' => 'Test todo',
            'due_date' => '2024-06-30',
            'categoryName' => 'Test Category'
        ]);

        $response->assertStatus(200); // Ensure todo is created successfully


        $todo = Todo::latest()->first(); // Get the latest created todo

        // Update the todo
        $updatedResponse = $this->putJson("/api/todos/{$todo->id}", [
            'content' => 'Updated test todo',
            'due_date' => '2024-07-01',
            'categoryName' => 'Test update Category'
        ]);

        $updatedResponse->assertStatus(200); // Ensure todo is updated successfully


        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'content' => 'Updated test todo',
            'due_date' => '2024-07-01',
        ]);



        $this->assertDatabaseHas('categories', [
            'name' => 'Test UpDate Category'
        ]);


        $todo = Todo::where('id', $todo->id)->first();
        $category = Category::orderByDesc('id')->first();

        $this->assertDatabaseHas('category_tasks', [
            'todo_id' => $todo->id,
            'category_id' =>$category->id,
        ]);

    }

      /** @test */
      //برای تست پاک کردن todo
      public function it_can_delete_a_todo()
      {
        $this->actingAs($this->user);
          $todo = Todo::factory()->create();

          $todo->delete();

          $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
      }


}
