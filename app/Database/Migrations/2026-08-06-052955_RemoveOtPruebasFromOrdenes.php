<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveOtPruebasFromOrdenes extends Migration
{
    public function up()
    {
        // Esto le dice a la base de datos de producción que elimine la columna
        $this->forge->dropColumn('ordenes', 'ot_pruebas');
    }

    public function down()
    {
        // En caso de querer deshacer, volvemos a crear la columna
        $this->forge->addColumn('ordenes', [
            'ot_pruebas' => [
                'type' => 'TEXT',
                'null' => true,
            ]
        ]);
    }
}
