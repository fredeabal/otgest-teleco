<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Test\AuthenticationTesting;

class SmtpControllerTest extends CIUnitTestCase
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
            'username' => 'admin_smtp',
            'email'    => 'adminsmtp@demo.com',
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

    public function testSmtpUpdate()
    {
        $admin = $this->createAdmin();

        $result = $this->actingAs($admin)
                       ->post('settings/smtp/update', [
                           'fromEmail'   => 'test@domain.com',
                           'fromName'    => 'Test Name',
                           'smtp_host'   => 'smtp.test.com',
                           'smtp_user'   => 'user_test',
                           'smtp_pass'   => 'new_password_123',
                           'smtp_port'   => 465,
                           'smtp_crypto' => 'ssl',
                           'mailType'    => 'html',
                           csrf_token()  => csrf_hash()
                       ]);

        $this->assertTrue($result->isRedirect());
        $result->assertSessionHas('message', 'Ajustes SMTP guardados exitosamente.');

        // Verificar usando el servicio de Settings
        $settings = service('settings');
        $this->assertEquals('test@domain.com', $settings->get('Email.fromEmail'));
        $this->assertEquals('smtp.test.com', $settings->get('Email.SMTPHost'));
        $this->assertEquals(465, $settings->get('Email.SMTPPort'));
    }

    public function testSmtpUpdateValidation()
    {
        $admin = $this->createAdmin();

        $result = $this->actingAs($admin)
                       ->post('settings/smtp/update', [
                           'smtp_host'   => '', // Host es requerido
                           'smtp_port'   => 'no-es-numero', // Puerto debe ser numérico
                           csrf_token()  => csrf_hash()
                       ]);

        $this->assertTrue($result->isRedirect());
        $result->assertSessionHas('errors');
    }
}
