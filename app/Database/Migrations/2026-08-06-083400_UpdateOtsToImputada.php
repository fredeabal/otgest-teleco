<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateOtsToImputada extends Migration
{
    public function up()
    {
        $this->db->query("UPDATE ordenes SET ot_imputada = 1");
    }

    public function down()
    {
        // No action needed for down
    }
}
