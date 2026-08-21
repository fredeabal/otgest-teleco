<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTodoCompletedToTodos extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('todo_completed', 'todos')) {
            $this->forge->addColumn('todos', [
                'todo_completed' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => false,
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('todo_completed', 'todos')) {
            $this->forge->dropColumn('todos', 'todo_completed');
        }
    }
}
