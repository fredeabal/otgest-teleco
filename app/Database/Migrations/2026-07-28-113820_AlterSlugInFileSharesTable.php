<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterSlugInFileSharesTable extends Migration
{
    public function up()
    {
        $fields = [
            'slug' => [
                'name'       => 'slug',
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
        ];
        $this->forge->modifyColumn('file_shares', $fields);
    }

    public function down()
    {
        $fields = [
            'slug' => [
                'name'       => 'slug',
                'type'       => 'VARCHAR',
                'constraint' => '12',
            ],
        ];
        $this->forge->modifyColumn('file_shares', $fields);
    }
}
