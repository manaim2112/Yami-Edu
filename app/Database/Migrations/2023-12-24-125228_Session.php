<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Session extends Migration
{
    public function up()
    {
        /**
         * session table
         * @field id is session id
         * @field name is session name, example 2023/2024
         * @field active is current session active
         * @field Timestamp
         */
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],

            'current' => [
                'type' => 'boolean',
                'default' => false,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey("name", "session_name");
        $this->forge->createTable('session');

         /**
         * session table
         * @field id is session id
         * @field name is session name, example 2023/2024
         * @field active is current session active
         * @field Timestamp
         */
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            
            'session_id' => [
                'type' => 'INT',
            ],

            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],

            'current' => [
                'type' => 'boolean',
                'default' => false,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey("name", "session_name");
        $this->forge->createTable('session_sub', true);
    }
    
    public function down()
    {
        $this->forge->dropTable('session', true);
        $this->forge->dropTable('session_sub', true);
    }
}
