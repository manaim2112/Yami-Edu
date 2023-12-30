<?php

namespace App\Database\Seeds;

use App\Models\Admin;
use App\Models\UserModel;
use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        for($i=0; $i < 1000; $i++) {
            $data = [
                'username' => 'user' . $i+1,
                'email'    => 'user' . $i+1 . '@mail.com',
                'password' => password_hash('12345', PASSWORD_DEFAULT),
            ];
            $userModel->save($data);
        }


        $admin = new Admin();
        $admin->save([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => password_hash("12345", PASSWORD_ARGON2I),
            'role' => 1,
        ]);
        
        for($i=0; $i < 1000; $i++) {
            $admin->save([
                'username' => 'pegawai' . $i,
                'email' => 'pegawai' . $i+1 . '@mail.com',
                'password' => password_hash("12345", PASSWORD_ARGON2I),
            ]);
        }
    }
}
