<?php

namespace App\Database\Migrations;

use App\Models\Admin as ModelsAdmin;
use CodeIgniter\Database\Migration;

class Admin extends Migration
{
    public function up()
    {
        /**
         * Admin migration
         * @field id is Admin id
         * @field username is Id alias of the user
         * @field email is email address of the user
         * @field password is Password of the user
         * @field role is role of the user, example 1 (administrator) or 2 (chief) or 3 (operator) or 4 (guru) or 5 (umum)
         */
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
            'role' => [
                'type' => "INT",
                'constraint' => 5,
                'default' => 5
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
        $this->forge->createTable('admin', true);

    }

    public function down()
    {
        $this->forge->dropTable('admin');
    }
}
