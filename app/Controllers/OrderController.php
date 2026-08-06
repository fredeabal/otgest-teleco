<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\TemplateModel;
use App\Models\ImageModel;
use CodeIgniter\API\ResponseTrait;

class OrderController extends BaseController
{
    use ResponseTrait;

    protected $orderModel;
    protected $templateModel;
    protected $imageModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->templateModel = new TemplateModel();
        $this->imageModel = new ImageModel();
    }

    // ---------------------------------------------------------------------
    // Listar todas las órdenes
    // ---------------------------------------------------------------------
    public function index()
    {
        $search = $this->request->getGet('search');

        $query = $this->orderModel->orderBy('ot_id', 'DESC');
        
        if (!empty($search)) {
            // Eliminar espacios de la búsqueda del usuario
            $cleanSearch = str_replace(' ', '', $search);
            
            // Obtener la instancia de DB para escapar correctamente los caracteres especiales (%, _)
            $db = \Config\Database::connect();
            $escSearch = $db->escapeLikeString($cleanSearch);
            $likeStr = "'%" . $escSearch . "%' ESCAPE '!'";

            $query->groupStart()
                  ->where("REPLACE(ot_numero, ' ', '') LIKE $likeStr", null, false)
                  ->orWhere("REPLACE(ot_cliente, ' ', '') LIKE $likeStr", null, false)
                  ->orWhere("REPLACE(ot_direccion, ' ', '') LIKE $likeStr", null, false)
                  ->orWhere("REPLACE(ot_tipo, ' ', '') LIKE $likeStr", null, false)
                  ->orWhere("REPLACE(ot_txt, ' ', '') LIKE $likeStr", null, false)
                  ->groupEnd();
        }
        
        $estado = $this->request->getGet('estado');
        $tipo = $this->request->getGet('tipo');
        $operadora = $this->request->getGet('operadora');
        $imputada = $this->request->getGet('imputada');
        $fecha_desde = $this->request->getGet('fecha_desde');
        $fecha_hasta = $this->request->getGet('fecha_hasta');

        if (!empty($estado)) {
            $query->where('ot_estado', $estado);
        }
        
        if ($imputada !== null && $imputada !== '') {
            $query->where('ot_imputada', $imputada);
        }
        
        if (!empty($tipo)) {
            $query->where('ot_tipo', $tipo);
        }
        
        if (!empty($operadora)) {
            $query->where('ot_operadora', $operadora);
        }
        
        if (!empty($fecha_desde)) {
            $f_desde = \DateTime::createFromFormat('d/m/Y', $fecha_desde);
            if ($f_desde) {
                $query->where('ot_fecha >=', $f_desde->format('Y-m-d'));
            } else {
                $query->where('ot_fecha >=', $fecha_desde); // Fallback if already Y-m-d
            }
        }
        
        if (!empty($fecha_hasta)) {
            $f_hasta = \DateTime::createFromFormat('d/m/Y', $fecha_hasta);
            if ($f_hasta) {
                $query->where('ot_fecha <=', $f_hasta->format('Y-m-d'));
            } else {
                $query->where('ot_fecha <=', $fecha_hasta);
            }
        }

        // Si el usuario no tiene permisos para ver todas (ej. no es admin), solo ve las suyas
        if (!auth()->user()->can('orders.view_all')) {
            $query->where('ot_usr', auth()->user()->id);
        }

        $data['orders'] = $query->paginate(50);
        $data['pager']  = $this->orderModel->pager;
        $data['search'] = $search;
        $data['estado'] = $estado;
        $data['tipo']   = $tipo;
        $data['operadora'] = $operadora;
        $data['imputada'] = $imputada;
        $data['fecha_desde'] = $fecha_desde;
        $data['fecha_hasta'] = $fecha_hasta;

        echo view('template/header', ['title' => 'Órdenes de Trabajo']);
        echo view('orders/index', $data);
        echo view('template/footer');
    }

    // ---------------------------------------------------------------------
    // Mostrar formulario de creación
    // ---------------------------------------------------------------------
    public function create()
    {
        if (auth()->user()->can('orders.view_all')) {
            $data['templates'] = $this->templateModel->findAll();
        } else {
            $data['templates'] = $this->templateModel->where('plantilla_usr', auth()->user()->id)->findAll();
        }

        echo view('template/header', ['title' => 'Nueva Orden']);
        echo view('orders/create', $data);
        echo view('template/footer');
    }

    // ---------------------------------------------------------------------
    // Procesar y guardar la nueva orden
    // ---------------------------------------------------------------------
    public function store()
    {
        $rules = [
            'ot_numero' => [
                'rules'  => 'required|is_unique[ordenes.ot_numero]|min_length[6]|max_length[10]',
                'errors' => [
                    'is_unique' => 'Ya existe una orden de trabajo con este número. No puedes duplicarla.'
                ]
            ],
            'ot_tipo'      => 'required',
            'ot_operadora' => 'required',
            'ot_cliente'   => 'required|min_length[5]',
            'ot_direccion' => 'required',
            'ot_contacto'  => 'permit_empty|numeric',
            'ot_txt'       => 'required',
            'ot_estado'    => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imputada = $this->request->getPost('ot_imputada') == 1 ? 1 : 0;

        $data = [
            'ot_numero'    => $this->request->getPost('ot_numero'),
            'ot_tipo'      => $this->request->getPost('ot_tipo'),
            'ot_operadora' => $this->request->getPost('ot_operadora'),
            'ot_cliente'   => $this->request->getPost('ot_cliente'),
            'ot_contacto'  => $this->request->getPost('ot_contacto'),
            'ot_direccion' => $this->request->getPost('ot_direccion'),
            'ot_txt'       => $this->request->getPost('ot_txt'),
            'ot_estado'    => $this->request->getPost('ot_estado'),
            'ot_imputada'  => $imputada,
            'ot_fecha'     => $this->request->getPost('ot_fecha') ?: date('Y-m-d'),
            'ot_usr'       => auth()->user()->id,
        ];

        if ($this->orderModel->insert($data)) {
            return redirect()->to('orders')->with('message', 'Orden creada exitosamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al crear la orden');
    }

    // ---------------------------------------------------------------------
    // Ver detalles de una orden
    // ---------------------------------------------------------------------
    public function show($id = null)
    {
        $order = $this->orderModel->find($id);

        if (!$order) {
            return redirect()->to('orders')->with('error', 'Registro no encontrado');
        }

        // Validación de permisos
        if (!auth()->user()->can('orders.view_all') && $order['ot_usr'] != auth()->user()->id) {
            return redirect()->to('orders')->with('error', 'No tienes permisos para ver esta orden');
        }

        $data['order'] = $order;
        $data['images'] = $this->imageModel->where('img_ot_id', $id)->findAll();
        
        $userProvider = auth()->getProvider();
        $user = $userProvider->findById($order['ot_usr']);
        $data['tecnico_nombre'] = $user ? $user->username : 'Usuario ID: ' . $order['ot_usr'];

        // Anterior Orden
        $canViewAll = auth()->user()->can('orders.view_all');
        $userId = auth()->user()->id;

        if (!$canViewAll) {
            $this->orderModel->where('ot_usr', $userId);
        }
        $data['prevOrder'] = $this->orderModel->where('ot_id <', $id)->orderBy('ot_id', 'DESC')->first();

        // Siguiente Orden
        if (!$canViewAll) {
            $this->orderModel->where('ot_usr', $userId);
        }
        $data['nextOrder'] = $this->orderModel->where('ot_id >', $id)->orderBy('ot_id', 'ASC')->first();

        echo view('template/header', ['title' => 'Ver Orden']);
        echo view('orders/show', $data);
        echo view('template/footer');
    }

    // ---------------------------------------------------------------------
    // Editar orden
    // ---------------------------------------------------------------------
    public function edit($id = null)
    {
        $order = $this->orderModel->find($id);

        if (!$order) {
            return redirect()->to('orders')->with('error', 'Registro no encontrado');
        }

        if (!auth()->user()->can('orders.view_all') && $order['ot_usr'] != auth()->user()->id) {
            return redirect()->to('orders')->with('error', 'No tienes permisos');
        }

        $data['order'] = $order;
        if (auth()->user()->can('orders.view_all')) {
            $data['templates'] = $this->templateModel->findAll();
        } else {
            $data['templates'] = $this->templateModel->where('plantilla_usr', auth()->user()->id)->findAll();
        }

        echo view('template/header', ['title' => 'Editar Orden']);
        echo view('orders/edit', $data);
        echo view('template/footer');
    }

    // ---------------------------------------------------------------------
    // Actualizar orden
    // ---------------------------------------------------------------------
    public function update($id = null)
    {
        $order = $this->orderModel->find($id);

        if (!$order) {
            return redirect()->to('orders')->with('error', 'Registro no encontrado');
        }

        if (!auth()->user()->can('orders.view_all') && $order['ot_usr'] != auth()->user()->id) {
            return redirect()->to('orders')->with('error', 'No tienes permisos');
        }

        $rules = [
            'ot_numero' => [
                'rules'  => "required|is_unique[ordenes.ot_numero,ot_id,{$id}]|min_length[6]|max_length[10]",
                'errors' => [
                    'is_unique' => 'Ya existe otra orden de trabajo con este número. No puedes duplicarla.'
                ]
            ],
            'ot_tipo'      => 'required',
            'ot_cliente'   => 'required|min_length[5]',
            'ot_direccion' => 'required',
            'ot_contacto'  => 'permit_empty|numeric',
            'ot_txt'       => 'required',
            'ot_estado'    => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imputada = $this->request->getPost('ot_imputada') == 1 ? 1 : 0;

        $otFecha = $this->request->getPost('ot_fecha');
        if ($otFecha) {
            $d = \DateTime::createFromFormat('d/m/Y', $otFecha);
            if ($d) $otFecha = $d->format('Y-m-d');
        }

        $data = [
            'ot_numero'    => $this->request->getPost('ot_numero'),
            'ot_tipo'      => $this->request->getPost('ot_tipo'),
            'ot_operadora' => $this->request->getPost('ot_operadora'),
            'ot_cliente'   => $this->request->getPost('ot_cliente'),
            'ot_contacto'  => $this->request->getPost('ot_contacto'),
            'ot_direccion' => $this->request->getPost('ot_direccion'),
            'ot_txt'       => $this->request->getPost('ot_txt'),
            'ot_estado'    => $this->request->getPost('ot_estado'),
            'ot_imputada'  => $imputada,
            'ot_fecha'     => $otFecha ?: $order['ot_fecha'],
            'ot_editado_usr' => auth()->user()->id,
            'ot_editado_fecha' => date('Y-m-d'),
        ];

        if ($this->orderModel->update($id, $data)) {
            return redirect()->to('orders/show/'.$id)->with('message', 'Orden actualizada correctamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al actualizar');
    }

    // ---------------------------------------------------------------------
    // Eliminar orden
    // ---------------------------------------------------------------------
    public function delete($id = null)
    {
        $order = $this->orderModel->find($id);

        if (!$order) {
            return redirect()->to('orders')->with('error', 'Registro no encontrado');
        }

        if (!auth()->user()->can('orders.view_all') && $order['ot_usr'] != auth()->user()->id) {
            return redirect()->to('orders')->with('error', 'No tienes permisos');
        }

        if ($this->orderModel->delete($id)) {
            return redirect()->to('orders')->with('message', 'Registro eliminado');
        }

        return redirect()->to('orders')->with('error', 'Error al eliminar');
    }

    // ---------------------------------------------------------------------
    // Enviar detalles de la orden por correo
    // ---------------------------------------------------------------------
    public function sendEmail($id = null)
    {
        $order = $this->orderModel->find($id);

        if (!$order) {
            return redirect()->to('orders')->with('error', 'Registro no encontrado');
        }

        $emailRules = [
            'recipient_email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'valid_email' => 'Por favor, introduce una dirección de correo válida.'
                ]
            ]
        ];

        if (!$this->validate($emailRules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $recipient = $this->request->getPost('recipient_email');
        
        $emailService = \Config\Services::email();
        $settings = service('settings');

        // Configurar emisor
        $fromEmail = $settings->get('Email.fromEmail') ?: 'no-reply@tudominio.com';
        $fromName  = $settings->get('Email.fromName') ?: 'OtGest';

        $emailService->setFrom($fromEmail, $fromName);
        $emailService->setTo($recipient);
        $emailService->setSubject("Detalles de la Orden de Trabajo " . $order['ot_numero']);

        // Plantilla HTML Premium del correo
        $logoUrl = base_url('assets/images/logos/email-logo.png');
        $messageBody = view('emails/order_details', [
            'order' => $order,
            'logoUrl' => $logoUrl
        ]);

        $emailService->setMessage($messageBody);

        if ($emailService->send()) {
            return redirect()->back()->with('message', "El correo con los detalles de la orden ha sido enviado a {$recipient}.");
        } else {
            log_message('error', 'Fallo al enviar correo de orden: ' . $emailService->printDebugger(['headers']));
            return redirect()->back()->with('error', 'Fallo al enviar el correo. Por favor, revisa tus Ajustes SMTP.');
        }
    }

    // ---------------------------------------------------------------------
    // Comprobar si el número de orden ya existe (AJAX)
    // ---------------------------------------------------------------------
    public function checkNumero()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso denegado']);
        }

        $numero = $this->request->getPost('ot_numero');
        $id = $this->request->getPost('ot_id'); // En caso de edición

        if (empty($numero)) {
            return $this->response->setJSON(['status' => 'ok']);
        }

        $query = $this->orderModel->where('ot_numero', $numero);
        if (!empty($id)) {
            $query->where('ot_id !=', $id);
        }

        $exists = $query->first();

        $csrfName = csrf_token();
        $csrfHash = csrf_hash();

        if ($exists) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'El número de orden ya existe.',
                'order_id' => $exists['ot_id'],
                'csrfToken' => $csrfHash
            ]);
        }

        return $this->response->setJSON([
            'status' => 'ok',
            'csrfToken' => $csrfHash
        ]);
    }
}
