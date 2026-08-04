<?php

namespace App\Controllers;

use App\Models\ImageModel;
use App\Models\OrderModel;

class ImageController extends BaseController
{
    protected $imageModel;
    protected $orderModel;

    public function __construct()
    {
        $this->imageModel = new ImageModel();
        $this->orderModel = new OrderModel();
    }

    // ---------------------------------------------------------------------
    // Subir imagen para una orden
    // ---------------------------------------------------------------------
    public function store()
    {
        $orderId = $this->request->getPost('ot_id');
        $order = $this->orderModel->find($orderId);

        if (!$order) {
            if ($this->request->isAJAX()) return $this->response->setStatusCode(404)->setJSON(['error' => 'Orden no encontrada']);
            return redirect()->back()->with('error', 'Orden no encontrada');
        }

        if (!auth()->user()->can('orders.view_all') && $order['ot_usr'] != auth()->user()->id) {
            if ($this->request->isAJAX()) return $this->response->setStatusCode(403)->setJSON(['error' => 'Sin permisos']);
            return redirect()->back()->with('error', 'Sin permisos para subir adjuntos a esta orden');
        }

        $file = $this->request->getFile('imagen');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/orders', $newName);

            $this->imageModel->insert([
                'img_nombre' => $newName,
                'img_ot_id'  => $orderId
            ]);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'success', 'filename' => $newName]);
            }
            return redirect()->back()->with('message', 'Imagen subida correctamente');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Error al subir la imagen']);
        }
        return redirect()->back()->with('error', 'Error al subir la imagen');
    }

    // ---------------------------------------------------------------------
    // Eliminar imagen
    // ---------------------------------------------------------------------
    public function delete($id = null)
    {
        $image = $this->imageModel->find($id);

        if (!$image) {
            return redirect()->back()->with('error', 'Imagen no encontrada');
        }

        $order = $this->orderModel->find($image['img_ot_id']);

        if (!auth()->user()->can('orders.view_all') && $order['ot_usr'] != auth()->user()->id) {
            return redirect()->back()->with('error', 'Sin permisos para eliminar esta imagen');
        }

        $filePath = FCPATH . 'uploads/orders/' . $image['img_nombre'];
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        if ($this->imageModel->delete($id)) {
            return redirect()->back()->with('message', 'Imagen eliminada correctamente');
        }

        return redirect()->back()->with('error', 'No se pudo eliminar el registro de la imagen');
    }
}
