<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Test\AuthenticationTesting;

class ProfileControllerTest extends CIUnitTestCase
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
            'username' => 'profile_user',
            'email'    => 'profile@demo.com',
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

    public function testUpdateProfile()
    {
        $user = $this->createUser();

        $result = $this->actingAs($user)
                       ->post('profile/update', [
                           'username' => 'profile_updated',
                           'email'    => 'updated@demo.com',
                           'phone'    => '+34 600111222',
                           csrf_token() => csrf_hash()
                       ]);

        $result->assertRedirectTo('profile');
        $result->assertSessionHas('message', 'Perfil actualizado.');

        // Verificar DB
        $this->seeInDatabase('users', ['username' => 'profile_updated']);
        $this->seeInDatabase('auth_identities', ['secret' => 'updated@demo.com']);
    }

    public function testUpdateProfilePassword()
    {
        $user = $this->createUser();

        $result = $this->actingAs($user)
                       ->post('profile/update', [
                           'username'         => 'profile_user',
                           'email'            => 'profile@demo.com',
                           'password'         => 'NewSecret123!',
                           'password_confirm' => 'NewSecret123!',
                           csrf_token()       => csrf_hash()
                       ]);

        $result->assertRedirectTo('profile');
        $result->assertSessionHas('message', 'Perfil actualizado.');

        // Verificar que la contraseña realmente cambió (intentando login)
        $credentials = [
            'email'    => 'profile@demo.com',
            'password' => 'NewSecret123!'
        ];
        $loginAttempt = auth()->attempt($credentials);
        $this->assertTrue($loginAttempt->isOK());
    }
}
