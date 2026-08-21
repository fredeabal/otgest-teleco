<?php

namespace Tests\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\OrderModel;

class OrderModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $migrate = true;
    protected $migrateOnce = false;
    protected $namespace = null;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testInsertAndFindOrder()
    {
        $model = new OrderModel();
        
        $data = [
            'ot_numero' => 'OT-MODEL-001',
            'ot_tipo' => 'Mantenimiento',
            'ot_cliente' => 'Cliente Alpha',
            'ot_direccion' => 'Avenida Principal 456',
            'ot_txt' => 'Descripción de la prueba del modelo',
            'ot_usr' => 1,
            'ot_fecha' => date('Y-m-d')
        ];

        $orderId = $model->insert($data);
        $this->assertIsNumeric($orderId);

        $order = $model->find($orderId);
        $this->assertNotNull($order);
        $this->assertEquals('OT-MODEL-001', $order['ot_numero']);
        $this->assertEquals('Cliente Alpha', $order['ot_cliente']);
    }

    public function testUpdateOrder()
    {
        $model = new OrderModel();
        
        $data = [
            'ot_numero' => 'OT-MODEL-002',
            'ot_tipo' => 'Instalación',
            'ot_cliente' => 'Cliente Beta',
            'ot_direccion' => 'Calle Secundaria 789',
            'ot_txt' => 'Test de update',
            'ot_usr' => 1,
            'ot_fecha' => date('Y-m-d')
        ];

        $orderId = $model->insert($data);
        
        // Modificar el registro
        $model->update($orderId, ['ot_cliente' => 'Cliente Gamma', 'ot_estado' => 'Completada']);
        
        $updatedOrder = $model->find($orderId);
        $this->assertEquals('Cliente Gamma', $updatedOrder['ot_cliente']);
        $this->assertEquals('Completada', $updatedOrder['ot_estado']);
    }

    public function testDeleteOrder()
    {
        $model = new OrderModel();
        
        $data = [
            'ot_numero' => 'OT-MODEL-003',
            'ot_tipo' => 'Reparación',
            'ot_cliente' => 'Cliente Delta',
            'ot_direccion' => 'Boulevard 321',
            'ot_txt' => 'Test de delete',
            'ot_usr' => 1,
            'ot_fecha' => date('Y-m-d')
        ];

        $orderId = $model->insert($data);
        $this->assertNotNull($model->find($orderId));
        
        $model->delete($orderId);
        $this->assertNull($model->find($orderId));
    }
}
