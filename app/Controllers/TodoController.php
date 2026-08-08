<?php

namespace App\Controllers;

use App\Models\TodoModel;

class TodoController extends BaseController
{
    protected $todoModel;

    public function __construct()
    {
        $this->todoModel = new TodoModel();
    }

    // ---------------------------------------------------------------------
    // Listar las tareas del usuario autenticado
    // ---------------------------------------------------------------------
    public function index()
    {
        $userId = auth()->user()->id;

        // Limpiar automáticamente tareas completadas antiguas de la base de datos
        $this->todoModel->where('todo_completed', 1)->delete();

        $data['pendingTodos']   = $this->todoModel->where('todo_usr', $userId)
                                                  ->where('todo_completed', 0)
                                                  ->orderBy('todo_id', 'DESC')
                                                  ->findAll();

        echo view('template/header', ['title' => 'Lista de Tareas (To-Do)']);
        echo view('todos/index', $data);
        echo view('template/footer');
    }

    // ---------------------------------------------------------------------
    // Guardar una nueva tarea
    // ---------------------------------------------------------------------
    public function store()
    {
        $rules = [
            'todo_title' => [
                'rules'  => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required'   => 'El título de la tarea es obligatorio.',
                    'min_length' => 'La tarea debe tener al menos 3 caracteres.',
                    'max_length' => 'La tarea no puede superar los 255 caracteres.',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'todo_usr'       => auth()->user()->id,
            'todo_title'     => $this->request->getPost('todo_title'),
            'todo_completed' => 0,
        ];

        if ($this->todoModel->insert($data)) {
            return redirect()->to('todos')->with('message', 'Tarea añadida correctamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al añadir la tarea');
    }

    // ---------------------------------------------------------------------
    // Eliminar una tarea
    // ---------------------------------------------------------------------
    public function delete($id = null)
    {
        $todo = $this->todoModel->find($id);

        if (!$todo || $todo['todo_usr'] != auth()->user()->id) {
            return redirect()->to('todos')->with('error', 'Tarea no encontrada o sin autorización.');
        }

        if ($this->todoModel->delete($id)) {
            return redirect()->to('todos')->with('message', 'Tarea eliminada exitosamente');
        }

        return redirect()->to('todos')->with('error', 'No se pudo eliminar la tarea.');
    }
}
