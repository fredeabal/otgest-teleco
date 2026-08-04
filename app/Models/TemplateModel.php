<?php

namespace App\Models;

use CodeIgniter\Model;

class TemplateModel extends Model
{
    protected $table            = 'plantillas';
    protected $primaryKey       = 'plantilla_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'plantilla_usr',
        'plantilla_nombre',
        'plantilla_txt'
    ];

    protected $useTimestamps = false;
}
