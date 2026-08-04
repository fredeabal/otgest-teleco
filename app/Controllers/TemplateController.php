<?php

namespace App\Controllers;

use App\Models\TemplateModel;

class TemplateController extends BaseController
{
    protected $templateModel;

    public function __construct()
    {
        $this->templateModel = new TemplateModel();
    }

    // ---------------------------------------------------------------------
    // Listar plantillas
    // ---------------------------------------------------------------------
    public function index()
    {
        $search = $this->request->getGet('search');
        $query = $this->templateModel;
        if (!auth()->user()->can('orders.view_all')) {
            $query = $query->where('plantilla_usr', auth()->user()->id);
        }

        if (!empty($search)) {
            $query->groupStart()
                  ->like('plantilla_nombre', $search)
                  ->orLike('plantilla_txt', $search)
                  ->groupEnd();
        }

        $data['templates'] = $query->findAll();
        $data['search'] = $search;

        echo view('template/header', ['title' => 'Mis Plantillas']);
        echo view('templates/index', $data);
        echo view('template/footer');
    }

    // ---------------------------------------------------------------------
    // Formulario crear
    // ---------------------------------------------------------------------
    public function create()
    {
        echo view('template/header', ['title' => 'Nueva Plantilla']);
        echo view('templates/create');
        echo view('template/footer');
    }

    // ---------------------------------------------------------------------
    // Procesar y guardar plantilla
    // ---------------------------------------------------------------------
    public function store()
    {
        $rules = [
            'plantilla_nombre' => 'required|min_length[3]',
            'plantilla_txt'    => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'plantilla_nombre' => $this->request->getPost('plantilla_nombre'),
            'plantilla_txt'    => $this->request->getPost('plantilla_txt'),
            'plantilla_usr'    => auth()->user()->id,
        ];

        if ($this->templateModel->insert($data)) {
            return redirect()->to('templates')->with('message', 'Plantilla creada con éxito');
        }

        return redirect()->back()->withInput()->with('error', 'No se pudo crear la plantilla');
    }

    // ---------------------------------------------------------------------
    // Formulario editar
    // ---------------------------------------------------------------------
    public function edit($id = null)
    {
        $template = $this->templateModel->find($id);

        if (!$template || (!auth()->user()->can('orders.view_all') && $template['plantilla_usr'] != auth()->user()->id)) {
            return redirect()->to('templates')->with('error', 'Registro no encontrado o sin permisos');
        }

        $data['template'] = $template;

        echo view('template/header', ['title' => 'Editar Plantilla']);
        echo view('templates/edit', $data);
        echo view('template/footer');
    }

    // ---------------------------------------------------------------------
    // Actualizar plantilla
    // ---------------------------------------------------------------------
    public function update($id = null)
    {
        $template = $this->templateModel->find($id);

        if (!$template || (!auth()->user()->can('orders.view_all') && $template['plantilla_usr'] != auth()->user()->id)) {
            return redirect()->to('templates')->with('error', 'Sin acceso');
        }

        $rules = [
            'plantilla_nombre' => 'required|min_length[3]',
            'plantilla_txt'    => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'plantilla_nombre' => $this->request->getPost('plantilla_nombre'),
            'plantilla_txt'    => $this->request->getPost('plantilla_txt'),
        ];

        if ($this->templateModel->update($id, $data)) {
            return redirect()->to('templates')->with('message', 'Plantilla actualizada');
        }

        return redirect()->back()->withInput()->with('error', 'Error al actualizar');
    }

    // ---------------------------------------------------------------------
    // Eliminar plantilla
    // ---------------------------------------------------------------------
    public function delete($id = null)
    {
        $template = $this->templateModel->find($id);

        if (!$template || (!auth()->user()->can('orders.view_all') && $template['plantilla_usr'] != auth()->user()->id)) {
            return redirect()->to('templates')->with('error', 'Sin acceso');
        }

        if ($this->templateModel->delete($id)) {
            return redirect()->to('templates')->with('message', 'Plantilla eliminada');
        }

        return redirect()->to('templates')->with('error', 'No se pudo eliminar');
    }
}
