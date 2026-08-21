<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Test\AuthenticationTesting;

class ImageControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;
    use AuthenticationTesting;

    protected $refresh = true;
    protected $migrate = true;
    protected $migrateOnce = false;
    protected $namespace = null;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function createUser(): User
    {
        $user = new User([
            'username' => 'image_tester',
            'email'    => 'imagetest@demo.com',
            'password' => 'secret1234'
        ]);
        $provider = auth()->getProvider();
        $provider->save($user);
        $user = $provider->findById($provider->getInsertID());
        $user->addGroup('user');
        $user->active = 1;
        $provider->save($user);
        return $user;
    }

    public function testStoreImageFailsWhenOrderNotFound()
    {
        $user = $this->createUser();

        $result = $this->actingAs($user)
                       ->post('images/store', [
                           'ot_id'      => 9999, // Orden que no existe
                           csrf_token() => csrf_hash()
                       ]);

        $this->assertTrue($result->isRedirect());
        $result->assertSessionHas('error', 'Orden no encontrada');
    }

    public function testStoreImageFailsWithoutFile()
    {
        $user = $this->createUser();
        
        $orderModel = model('OrderModel');
        $orderId = $orderModel->insert([
            'ot_numero'    => 'OT-IMG-01',
            'ot_tipo'      => 'Avería',
            'ot_cliente'   => 'Cliente A',
            'ot_direccion' => 'Dir A',
            'ot_txt'       => 'Test',
            'ot_estado'    => 'Pendiente',
            'ot_usr'       => $user->id,
            'ot_fecha'     => date('Y-m-d')
        ]);

        $result = $this->actingAs($user)
                       ->post('images/store', [
                           'ot_id'      => $orderId,
                           // Sin archivo
                           csrf_token() => csrf_hash()
                       ]);

        $this->assertTrue($result->isRedirect());
        $result->assertSessionHas('error', 'Error al subir la imagen');
    }

    public function testDeleteImageFailsWhenImageNotFound()
    {
        $user = $this->createUser();

        $result = $this->actingAs($user)
                       ->post('images/delete/9999', [ // Imagen que no existe
                           csrf_token() => csrf_hash()
                       ]);

        $this->assertTrue($result->isRedirect());
        $result->assertSessionHas('error', 'Imagen no encontrada');
    }
}
