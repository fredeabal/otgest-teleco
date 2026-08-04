<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOtgestTables extends Migration
{
    public function up()
    {
        // Tabla ordenes
        $this->forge->addField([
            'ot_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ot_numero' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'ot_tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'ot_operadora' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'ot_cliente' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'ot_direccion' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'ot_txt' => [
                'type' => 'TEXT',
            ],
            'ot_usr' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'ot_fecha' => [
                'type' => 'DATE',
            ],
            'ot_imputada' => [
                'type'       => 'INT',
                'constraint' => 1,
                'null'       => true,
            ],
            'ot_pruebas' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'ot_editado_usr' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'ot_editado_fecha' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'ot_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('ot_id', true);
        $this->forge->createTable('ordenes');

        // Tabla imagenes
        $this->forge->addField([
            'img_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'img_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'img_ot_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
        ]);
        $this->forge->addKey('img_id', true);
        $this->forge->createTable('imagenes');

        // Tabla plantillas
        $this->forge->addField([
            'plantilla_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'plantilla_usr' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'plantilla_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'plantilla_txt' => [
                'type' => 'TEXT',
            ],
        ]);
        $this->forge->addKey('plantilla_id', true);
        $this->forge->createTable('plantillas');
    }

    public function down()
    {
        $this->forge->dropTable('plantillas');
        $this->forge->dropTable('imagenes');
        $this->forge->dropTable('ordenes');
    }
}
