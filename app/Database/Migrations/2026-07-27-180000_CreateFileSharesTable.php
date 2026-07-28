<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFileSharesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '12',
            ],
            'user_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'filename' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'storage_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'file_size' => [
                'type'       => 'BIGINT',
            ],
            'mime_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'download_limit' => [
                'type'       => 'INTEGER',
                'null'       => true,
            ],
            'download_count' => [
                'type'       => 'INTEGER',
                'default'    => 0,
            ],
            'is_public' => [
                'type'       => 'TINYINT',
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('file_shares', true);
    }

    public function down()
    {
        $this->forge->dropTable('file_shares', true);
    }
}
