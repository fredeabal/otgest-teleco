<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Test\AuthenticationTesting;

class RolesControllerTest extends CIUnitTestCase
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
            'username' => 'admin_roles',
            'email'    => 'adminroles@demo.com',
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

    public function testUpdateRoles()
    {
        $admin = $this->createAdmin();

        // Asegurarse de que el grupo 'user' exista antes de la actualización
        $result = $this->actingAs($admin)
                       ->post('roles/update/user', [
                           'permissions' => ['orders.view_all'],
                           csrf_token() => csrf_hash()
                       ]);

        $result->assertRedirectTo('roles');
        $result->assertSessionHas('message', 'Permisos actualizados.');

        // Verificar base de datos
        $this->seeInDatabase('auth_group_permissions', [
            'group'      => 'user',
            'permission' => 'orders.view_all'
        ]);
    }

    public function testUpdateSuperadminCannotLoseAdminPermissions()
    {
        $admin = $this->createAdmin();

        // Actualizamos intentando vaciar todos los permisos
        $result = $this->actingAs($admin)
                       ->post('roles/update/superadmin', [
                           'permissions' => [], // Vacío
                           csrf_token() => csrf_hash()
                       ]);

        $result->assertRedirectTo('roles');

        // Aunque mandamos vacío, debería habernos forzado admin.users, admin.roles, admin.settings
        $this->seeInDatabase('auth_group_permissions', [
            'group'      => 'superadmin',
            'permission' => 'admin.users'
        ]);
        $this->seeInDatabase('auth_group_permissions', [
            'group'      => 'superadmin',
            'permission' => 'admin.roles'
        ]);
    }
}
