<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAutoDestroyToFileShares extends Migration
{
    public function up()
    {
        $this->forge->addColumn('file_shares', [
            'auto_destroy' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('file_shares', 'auto_destroy');
    }
}
