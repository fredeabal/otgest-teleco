<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Test\AuthenticationTesting;

class MaintenanceControllerTest extends CIUnitTestCase
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
            'username' => 'admin_maint',
            'email'    => 'adminmaint@demo.com',
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

    public function testClearSessions()
    {
        $admin = $this->createAdmin();

        $result = $this->actingAs($admin)
                       ->post('settings/maintenance/clear-sessions', [
                           csrf_token() => csrf_hash()
                       ]);

        $this->assertTrue($result->isRedirect());
        $result->assertSessionHas('message'); // Should have a message like "Se limpiaron X archivos..."
    }

    public function testOptimizeDb()
    {
        $admin = $this->createAdmin();

        $result = $this->actingAs($admin)
                       ->post('settings/maintenance/optimize-db', [
                           csrf_token() => csrf_hash()
                       ]);

        $this->assertTrue($result->isRedirect());
        $result->assertSessionHas('message', 'La base de datos SQLite ha sido desfragmentada y optimizada correctamente.');
    }
}
