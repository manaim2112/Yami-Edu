<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserSesi extends Migration
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
            
            'user_id' => [
                'type' => 'INT',
            ],

            'kelas' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
            ],
            'jabat' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
            ]
            ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('userSesi', true);
    }

    public function down()
    {
        $this->forge->dropTable('userSesi', true);
    }
}
