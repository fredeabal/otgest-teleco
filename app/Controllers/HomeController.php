<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    // ---------------------------------------------------------------------
    // Método principal: Redirecciona al login o dashboard de OtGest
    // ---------------------------------------------------------------------
    public function index()
    {
        if (auth()->loggedIn()) {
            return redirect()->to('dashboard');
        }
        return redirect()->to('login');
    }
}
