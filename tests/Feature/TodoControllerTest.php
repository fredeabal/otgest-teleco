<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Test\AuthenticationTesting;

class TodoControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;
    use AuthenticationTesting;

    protected $refresh = true;
    protected $migrate = true;
    protected $migrateOnce = false;
    protected $namespace = null;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testStoreTodo()
    {
        // 1. Crear usuario
        $user = new User([
            'username' => 'todo_tester',
            'email'    => 'todotest@demo.com',
            'password' => 'secret1234'
        ]);
        $provider = auth()->getProvider();
        $provider->save($user);
        $user = $provider->findById($provider->getInsertID());
        $user->addGroup('user');
        $user->active = 1;
        $provider->save($user);

        // 2. Hacer POST a /todos/store
        $result = $this->actingAs($user)
                       ->post('todos/store', [
                           'todo_title' => 'Nueva tarea HTTP',
                           csrf_token() => csrf_hash()
                       ]);

        // 3. Verificar redirección y base de datos
        $result->assertRedirectTo('todos');
        $result->assertSessionHas('message', 'Tarea añadida correctamente');
        
        $this->seeInDatabase('todos', [
            'todo_usr' => $user->id,
            'todo_title' => 'Nueva tarea HTTP'
        ]);
    }

    public function testDeleteTodo()
    {
        $user = new User([
            'username' => 'todo_deleter',
            'email'    => 'tododelete@demo.com',
            'password' => 'secret1234'
        ]);
        $provider = auth()->getProvider();
        $provider->save($user);
        $user = $provider->findById($provider->getInsertID());
        $user->addGroup('user');
        $user->active = 1;
        $provider->save($user);

        // Crear Todo directamente
        $todoModel = model('TodoModel');
        $todoId = $todoModel->insert([
            'todo_usr' => $user->id,
            'todo_title' => 'Tarea a eliminar',
            'todo_completed' => 0
        ]);

        $this->seeInDatabase('todos', ['todo_id' => $todoId]);

        // Eliminar por endpoint
        $result = $this->actingAs($user)
                       ->post('todos/delete/' . $todoId, [
                           csrf_token() => csrf_hash()
                       ]);

        $result->assertRedirectTo('todos');
        $result->assertSessionHas('message', 'Tarea eliminada exitosamente');
        
        $this->dontSeeInDatabase('todos', ['todo_id' => $todoId]);
    }

    public function testToggleTodo()
    {
        $user = new User([
            'username' => 'todo_toggler',
            'email'    => 'todotoggle@demo.com',
            'password' => 'secret1234'
        ]);
        $provider = auth()->getProvider();
        $provider->save($user);
        $user = $provider->findById($provider->getInsertID());
        $user->addGroup('user');
        $user->active = 1;
        $provider->save($user);

        // Crear Todo directamente
        $todoModel = model('TodoModel');
        $todoId = $todoModel->insert([
            'todo_usr' => $user->id,
            'todo_title' => 'Tarea a completar',
            'todo_completed' => 0
        ]);

        // Completar por endpoint (que internamente hace delete en este controlador)
        $result = $this->actingAs($user)
                       ->post('todos/toggle/' . $todoId, [
                           csrf_token() => csrf_hash()
                       ]);

        $result->assertRedirectTo('todos');
        $result->assertSessionHas('message', '¡Tarea completada!');
        
        $this->dontSeeInDatabase('todos', ['todo_id' => $todoId]);
    }
}
