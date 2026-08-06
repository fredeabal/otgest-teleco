<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'ordenes';
    protected $primaryKey       = 'ot_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'ot_numero',
        'ot_tipo',
        'ot_operadora',
        'ot_cliente',
        'ot_contacto',
        'ot_direccion',
        'ot_txt',
        'ot_usr',
        'ot_fecha',
        'ot_imputada',
        'ot_editado_usr',
        'ot_editado_fecha',
        'ot_estado'
    ];

    protected $useTimestamps = false;
}
