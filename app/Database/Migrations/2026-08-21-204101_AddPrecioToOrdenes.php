<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPrecioToOrdenes extends Migration
{
    public function up()
    {
        $fields = [
            'ot_precio' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
                'null'       => false,
            ]
        ];
        $this->forge->addColumn('ordenes', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('ordenes', 'ot_precio');
    }
}
