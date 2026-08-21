<?php

namespace Tests\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\TodoModel;

class TodoModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $migrate = true;
    protected $migrateOnce = false;
    protected $namespace = null;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testInsertAndFindTodo()
    {
        $model = new TodoModel();
        
        $data = [
            'todo_usr' => 1,
            'todo_title' => 'Comprar pan',
            'todo_completed' => 0
        ];

        $todoId = $model->insert($data);
        $this->assertIsNumeric($todoId);

        $todo = $model->find($todoId);
        $this->assertNotNull($todo);
        $this->assertEquals('Comprar pan', $todo['todo_title']);
        $this->assertEquals(0, $todo['todo_completed']);
    }

    public function testUpdateTodo()
    {
        $model = new TodoModel();
        
        $data = [
            'todo_usr' => 1,
            'todo_title' => 'Llamar al cliente',
            'todo_completed' => 0
        ];

        $todoId = $model->insert($data);
        
        // Marcar como completado
        $model->update($todoId, ['todo_completed' => 1]);
        
        $updatedTodo = $model->find($todoId);
        $this->assertEquals(1, $updatedTodo['todo_completed']);
    }

    public function testDeleteTodo()
    {
        $model = new TodoModel();
        
        $data = [
            'todo_usr' => 1,
            'todo_title' => 'Tarea a borrar',
            'todo_completed' => 0
        ];

        $todoId = $model->insert($data);
        $this->assertNotNull($model->find($todoId));
        
        $model->delete($todoId);
        $this->assertNull($model->find($todoId));
    }
}
