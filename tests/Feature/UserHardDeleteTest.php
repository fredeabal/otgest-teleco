<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Test\AuthenticationTesting;
use Tests\Support\Database\Seeds\OtgestTestSeeder;

class UserHardDeleteTest extends CIUnitTestCase
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

    public function testUserHardDeleteAndCascade()
    {
        // 1. Crear un usuario superadmin para realizar el borrado
        $admin = new User([
            'username' => 'admin_tester',
            'email'    => 'admin@demo.com',
            'password' => 'secret1234'
        ]);
        $adminProvider = auth()->getProvider();
        $adminProvider->save($admin);
        $admin = $adminProvider->findById($adminProvider->getInsertID());
        $admin->addGroup('superadmin');
        $admin->active = 1;
        $adminProvider->save($admin);

        // 2. Crear un usuario normal que será eliminado
        $targetUser = new User([
            'username' => 'target_user',
            'email'    => 'target@demo.com',
            'password' => 'secret1234',
            'phone'    => '+34 600 000 000'
        ]);
        $targetProvider = auth()->getProvider();
        $targetProvider->save($targetUser);
        $targetUser = $targetProvider->findById($targetProvider->getInsertID());
        $targetUser->addGroup('user');
        $targetUser->active = 1;
        $targetProvider->save($targetUser);

        // 3. Crear una OT (Orden de Trabajo) asignada a targetUser
        $orderModel = model('OrderModel');
        $orderId = $orderModel->insert([
            'ot_numero' => 'OT-TEST-001',
            'ot_tipo' => 'Instalación',
            'ot_cliente' => 'Cliente Test',
            'ot_direccion' => 'Calle Falsa 123',
            'ot_txt' => 'Test OT',
            'ot_usr' => $targetUser->id,
            'ot_fecha' => date('Y-m-d')
        ]);

        // Verificar que todo se creó correctamente
        $this->seeInDatabase('users', ['id' => $targetUser->id]);
        $this->seeInDatabase('auth_identities', ['user_id' => $targetUser->id]);
        $this->seeInDatabase('ordenes', ['ot_id' => $orderId, 'ot_usr' => $targetUser->id]);

        // 4. Iniciar sesión como superadmin
        $result = $this->actingAs($admin)
                       ->post('users/delete/' . $targetUser->id, [csrf_token() => csrf_hash()]);

        $result->assertRedirectTo('users');
        $result->assertSessionHas('message', 'Usuario y sus OTs eliminados definitivamente.');

        // 5. Verificar Hard Delete de usuario
        $this->dontSeeInDatabase('users', ['id' => $targetUser->id]);
        
        // 6. Verificar Hard Delete de la identidad
        $this->dontSeeInDatabase('auth_identities', ['user_id' => $targetUser->id]);
        
        // 7. Verificar Borrado en cascada de las OTs
        $this->dontSeeInDatabase('ordenes', ['ot_id' => $orderId]);
    }
}
