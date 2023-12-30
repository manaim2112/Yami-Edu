<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Activity extends Migration
{
    public function up()
    {
        /**
         * Activity migration
         * @field id is id cbt
         * @field admin_id is ketua activity
         * @field admin_name is name of ketua activity
         * @field jenis is jenis activity, example cbt, seminar, rapat, workshop, etc
         * @field status is progress actifity, example START, END,
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
                'constraint' => 11,
            ],
            'admin_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'admin_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'jenis' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => 'start'
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey("name", "activity_name");
        $this->forge->createTable('activities');

        /**
         * Activity_team migration
         * @field id is id cbt
         * @field admin_id is ketua activity
         * @field admin_name is name of ketua activity
         * @field jenis is jenis activity, example cbt, seminar, rapat, workshop, etc
         * @field status is progress actifity, example START, END,
         */

         $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'activity_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'admin_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'alias' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('activities_teams');
        
        
        
        
    }
    
    public function down()
    {
        $this->forge->dropTable("activities", true);
        $this->forge->dropTable('activities_teams', true);
    }
}
