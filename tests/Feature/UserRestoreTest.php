<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Entities\User;

class UserRestoreTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testRestoreDeletedUserFlow()
    {
        // 1. Crear un usuario normal (simular BD inicial)
        $users = auth()->getProvider();
        
        $user = new User([
            'username' => 'testuser',
            'email'    => 'test@example.com',
            'password' => 'secret123'
        ]);
        $users->save($user);
        
        $user = $users->findById($users->getInsertID());
        $user->addGroup('supervisor'); // asignar rol
        
        // Verificamos que se creó bien
        $this->assertNotNull($user->id);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertNull($user->deleted_at);

        // 2. Simular que el admin elimina (soft delete) a este usuario
        $users->delete($user->id);
        
        // Verificamos que el usuario está soft deleted
        $deletedUser = $users->withDeleted()->findById($user->id);
        $this->assertNotNull($deletedUser->deleted_at);

        // 3. Petición POST a crear usuario con el mismo email/username
        // Autenticar al usuario para que auth()->user() funcione en el controlador
        auth()->login($user);

        // Usaremos call para llamar a la ruta de store
        $result = $this->withRoutes([
            ['post', 'users/store', 'UsersController::store'],
            ['post', 'users/restore/(:num)', 'UsersController::restore/$1']
        ])
        ->post('users/store', [
            csrf_token() => csrf_hash(),
            'username' => 'testuser',
            'email'    => 'test@example.com',
            'password' => 'newpassword123',
            'pass_confirm' => 'newpassword123',
            'phone'    => '+34600000000',
            'group'    => 'supervisor',
            'active'   => '1'
        ]);

        // Verificamos que la redirección contenga los datos de restauración en sesión
        $result->assertRedirect();
        
        // Obtenemos la sesión directamente
        $restoreData = session()->get('restore_user_data');
        
        $this->assertNotNull($restoreData, "Se esperaba que 'restore_user_data' estuviera en sesión");
        $this->assertEquals($user->id, $restoreData['id']);
        $this->assertEquals('testuser', $restoreData['username']);
        $this->assertEquals('newpassword123', $restoreData['password']);

        // 4. Simulamos la confirmación del admin en el SweetAlert2 llamando a restore
        $result = $this->post('users/restore/' . $user->id, array_merge(
            $restoreData,
            [csrf_token() => csrf_hash()]
        ));

        $result->assertRedirectTo('users');
        
        if (session()->has('error')) {
            echo "\nERROR DETECTED: " . session()->get('error') . "\n";
        }
        
        $result->assertSessionHas('message');

        // 5. Verificamos que el usuario ha sido restaurado en la base de datos
        $restoredUser = $users->findById($user->id);
        
        $this->assertNotNull($restoredUser, "El usuario no fue restaurado correctamente");
        $this->assertNull($restoredUser->deleted_at, "deleted_at debería ser null");
        $this->assertEquals('testuser', $restoredUser->username);
    }
}
