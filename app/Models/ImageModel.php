<?php

namespace App\Models;

use CodeIgniter\Model;

class ImageModel extends Model
{
    protected $table            = 'imagenes';
    protected $primaryKey       = 'img_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'img_nombre',
        'img_ot_id'
    ];

    protected $useTimestamps = false;
}
