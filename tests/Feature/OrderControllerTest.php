<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Test\AuthenticationTesting;

class OrderControllerTest extends CIUnitTestCase
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

    private function createUser(string $username, string $group): User
    {
        $user = new User([
            'username' => $username,
            'email'    => "{$username}@demo.com",
            'password' => 'secret1234'
        ]);
        $provider = auth()->getProvider();
        $provider->save($user);
        $user = $provider->findById($provider->getInsertID());
        $user->addGroup($group);
        $user->active = 1;
        $provider->save($user);
        return $user;
    }

    public function testStoreOrder()
    {
        $user = $this->createUser('tecnico1', 'user');

        $result = $this->actingAs($user)
                       ->post('orders/store', [
                           'ot_numero'    => 'OT-100200',
                           'ot_tipo'      => 'Avería',
                           'ot_operadora' => 'Movistar',
                           'ot_cliente'   => 'Juan Perez',
                           'ot_direccion' => 'Calle 1',
                           'ot_txt'       => 'Problema con la fibra',
                           'ot_estado'    => 'Pendiente',
                           'ot_imputada'  => 0,
                           csrf_token()   => csrf_hash()
                       ]);

        $result->assertRedirectTo('orders');
        $result->assertSessionHas('message', 'Orden creada exitosamente');
        
        $this->seeInDatabase('ordenes', [
            'ot_numero' => 'OT-100200',
            'ot_usr'    => $user->id
        ]);
    }

    public function testStoreOrderFailsValidation()
    {
        $user = $this->createUser('tecnico2', 'user');

        $result = $this->actingAs($user)
                       ->post('orders/store', [
                           'ot_numero'    => 'OT-1', // Demasiado corto (min 6)
                           csrf_token()   => csrf_hash()
                       ]);

        // Validation error redirect
        $this->assertTrue($result->isRedirect());
        $result->assertSessionHas('errors');
    }

    public function testDeleteOrder()
    {
        $user = $this->createUser('tecnico3', 'user');

        $orderModel = model('OrderModel');
        $orderId = $orderModel->insert([
            'ot_numero'    => 'OT-100300',
            'ot_tipo'      => 'Avería',
            'ot_cliente'   => 'Cliente A',
            'ot_direccion' => 'Dir A',
            'ot_txt'       => 'Test',
            'ot_estado'    => 'Pendiente',
            'ot_usr'       => $user->id,
            'ot_fecha'     => date('Y-m-d')
        ]);

        $result = $this->actingAs($user)
                       ->post("orders/delete/{$orderId}", [
                           csrf_token() => csrf_hash()
                       ]);

        $result->assertRedirectTo('orders');
        $result->assertSessionHas('message', 'Registro eliminado');
        
        $this->dontSeeInDatabase('ordenes', ['ot_id' => $orderId]);
    }

    public function testUserCannotDeleteOthersOrder()
    {
        $user1 = $this->createUser('tecnico_a', 'user');
        $user2 = $this->createUser('tecnico_b', 'user');

        $orderModel = model('OrderModel');
        $orderId = $orderModel->insert([
            'ot_numero'    => 'OT-100400',
            'ot_tipo'      => 'Avería',
            'ot_cliente'   => 'Cliente B',
            'ot_direccion' => 'Dir B',
            'ot_txt'       => 'Test',
            'ot_estado'    => 'Pendiente',
            'ot_usr'       => $user1->id,
            'ot_fecha'     => date('Y-m-d')
        ]);

        // User2 intenta borrar la OT de User1
        $result = $this->actingAs($user2)
                       ->post("orders/delete/{$orderId}", [
                           csrf_token() => csrf_hash()
                       ]);

        $result->assertRedirectTo('orders');
        $result->assertSessionHas('error', 'No tienes permisos');
        
        $this->seeInDatabase('ordenes', ['ot_id' => $orderId]);
    }
}
