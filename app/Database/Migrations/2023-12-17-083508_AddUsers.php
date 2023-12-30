<?php

namespace App\Database\Migrations;

use App\Models\UserModel;
use CodeIgniter\Database\Migration;

class AddUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey("username", 'username');
        $this->forge->addUniqueKey("email", 'email');
        $this->forge->createTable('users');
        
        
        $this->forge->addField([
            'id' => [ 
                'type' => 'INT',
                'constraint' => '5',
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
                ]
                
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('kelas');
    }

    public function down()
    {
        $this->forge->dropTable("users");
    }
}
