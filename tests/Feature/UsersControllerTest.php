<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Test\AuthenticationTesting;

class UsersControllerTest extends CIUnitTestCase
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

    private function createAdmin(): User
    {
        $user = new User([
            'username' => 'admin_test',
            'email'    => 'admin@demo.com',
            'password' => 'secret1234'
        ]);
        $provider = auth()->getProvider();
        $provider->save($user);
        $user = $provider->findById($provider->getInsertID());
        $user->addGroup('superadmin');
        $user->active = 1;
        $provider->save($user);
        return $user;
    }

    public function testStoreUser()
    {
        $admin = $this->createAdmin();

        $result = $this->actingAs($admin)
                       ->post('users/store', [
                           'username' => 'nuevotecnico',
                           'email'    => 'tecnico@demo.com',
                           'password' => 'Password123',
                           'pass_confirm' => 'Password123',
                           'group'    => 'user',
                           csrf_token() => csrf_hash()
                       ]);

        $result->assertRedirectTo('users');
        $result->assertSessionHas('message', 'Usuario creado.');
        
        $this->seeInDatabase('users', ['username' => 'nuevotecnico']);
    }

    public function testStoreUserFailsValidation()
    {
        $admin = $this->createAdmin();

        $result = $this->actingAs($admin)
                       ->post('users/store', [
                           'username' => 'nu', // Muy corto
                           'email'    => 'no-email', // Correo inválido
                           csrf_token() => csrf_hash()
                       ]);

        $this->assertTrue($result->isRedirect());
        $result->assertSessionHas('errors');
    }

    public function testToggleActiveUser()
    {
        $admin = $this->createAdmin();

        // Crear usuario normal
        $targetUser = clone clone $admin; // solo para usar la misma clase Entity
        $targetUser = new User([
            'username' => 'targettoggle',
            'email'    => 'toggle@demo.com',
            'password' => 'secret1234'
        ]);
        $provider = auth()->getProvider();
        $provider->save($targetUser);
        $targetUserId = $provider->getInsertID();
        
        // No está baneado por defecto
        
        $result = $this->actingAs($admin)
                       ->post("users/toggle-active/{$targetUserId}", [
                           csrf_token() => csrf_hash()
                       ]);

        $result->assertRedirectTo('users');
        $result->assertSessionHas('message', 'Usuario desactivado.');
        
        // Al volver a hacer toggle, se reactiva
        $result2 = $this->actingAs($admin)
                       ->post("users/toggle-active/{$targetUserId}", [
                           csrf_token() => csrf_hash()
                       ]);

        $result2->assertRedirectTo('users');
        $result2->assertSessionHas('message', 'Usuario activado.');
    }
}
