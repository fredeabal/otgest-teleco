<?php

namespace App\Controllers;

class GuideController extends BaseController
{
    // ---------------------------------------------------------------------
    // Mostrar la guía de uso / manual de usuario
    // ---------------------------------------------------------------------
    public function index()
    {
        $data = [
            'title' => 'Guía de Uso'
        ];

        echo view('template/header', $data);
        echo view('guide/index', $data);
        echo view('template/footer');
    }
}
