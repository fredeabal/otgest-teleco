<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddContactoToOrdenes extends Migration
{
    public function up()
    {
        $fields = [
            'ot_contacto' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'after'      => 'ot_cliente'
            ],
        ];
        $this->forge->addColumn('ordenes', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('ordenes', 'ot_contacto');
    }
}
