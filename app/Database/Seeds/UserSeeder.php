<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = auth()->getProvider();

        if (ENVIRONMENT === 'production' && $users->countAllResults() > 0) {
            echo "¡ERROR! No puedes ejecutar este Seeder porque ya existen usuarios. Destruiría todos los datos.\n";
            return;
        }

        // Desactivar claves foráneas para permitir truncar tablas en SQLite
        $this->db->query('PRAGMA foreign_keys = OFF;');

        // Eliminar usuarios y permisos existentes
        $this->db->table('auth_identities')->truncate();
        $this->db->table('auth_groups_users')->truncate();
        $this->db->table('users')->truncate();
        $this->db->table('auth_group_permissions')->truncate();

        // Reactivar claves foráneas
        $this->db->query('PRAGMA foreign_keys = ON;');

        // Crear único Superadmin inicial
        $admin = new User([
            'username' => 'admin',
            'email'    => 'admin@demo.com',
            'phone'    => '+34000000000',
            'password' => 'admin1234',
        ]);
        $users->save($admin);

        // Sembrar permisos por defecto
        $defaultPermissions = [
            // Permisos de Superadmin
            ['group' => 'superadmin', 'permission' => 'admin.users'],
            ['group' => 'superadmin', 'permission' => 'admin.roles'],
            ['group' => 'superadmin', 'permission' => 'admin.settings'],
            
            // Permisos de Supervisor
            ['group' => 'supervisor', 'permission' => 'admin.users'],
        ];

        $now = date('Y-m-d H:i:s');
        foreach ($defaultPermissions as &$p) {
            $p['created_at'] = $now;
        }

        $this->db->table('auth_group_permissions')->insertBatch($defaultPermissions);

        // Obtener objeto completo con ID, asignarle el grupo superadmin y activarlo
        $admin = $users->findById($users->getInsertID());
        $admin->addGroup('superadmin');
        $admin->activate();

        // Establecer puerto SMTP predeterminado en 587 y formato HTML
        $settings = service('settings');
        $settings->set('Email.SMTPPort', 587);
        $settings->set('Email.mailType', 'html');
    }
}
