<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Models\GroupModel;

class AuthSeeder extends Seeder
{
    public function run()
    {
        $groups = model(GroupModel::class);

        $groups->insert(['name' => 'admin',    'description' => 'Administrador del sistema']);
        $groups->insert(['name' => 'profesor', 'description' => 'Profesor del instituto']);
        $groups->insert(['name' => 'alumno',   'description' => 'Alumno del instituto']);

        $users = model(UserModel::class);

        $admin = new User([
            'username' => 'admin',
            'email'    => 'admin@instituto.test',
            'password' => 'admin123',
        ]);

        $users->save($admin);
        $admin = $users->findById($users->getInsertID());
        $admin->activate();
        $users->save($admin);

        $admin->addGroup('admin');
    }
}