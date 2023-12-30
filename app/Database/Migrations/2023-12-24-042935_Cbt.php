<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Cbt extends Migration
{
    public function up()
    {

        /**
         * CBT migration
         * @field id is id cbt
         * @field admin_id is admin creator cbt
         * @field name is name of cbt
         * @field room is class of cbt room1|room2|room3|...
         * @field tag is tag on cbt
         * @field start_date is start date of cbt and Finished date of cbt example 13123124|2042042049
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
                'constraint' => 11
            ],
            'admin_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'room' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'tag' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'start' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('cbt');

        /**
         * Cbt_room migration table
         * @field id is cbt_room id
         * @field name is cbt_room name
         * @field users is list of users in cbt_room, example : |21|23|1|2|3|4|5|6|7|8|9|...|
         * 
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
                'constraint' => 11
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 11,
            ],
            'alias' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'users' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('name', 'room_name');
        $this->forge->createTable('cbt_room');


        /**
         * Cbt_exam migration table
         * @field id is cbt_room id
         * @field cbt_id is id of Cbt
         * @field question is question exam 
         * @field jenis is cbt type of question, example mc (multiplechoice), m (matching), tf (true or false), v (voice),  f (file), ts (text short), tl (text long)
         * @field data is data content of jenis selection, example [1, 3] or [true], etc
         * @field answer is matching feedback user to question answer
         * @field score is score of exam question
         * @field level is level of exam question, example c1|c2|c3|c4|c5|c6
         */
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'cbt_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'question' => [
                'type' => 'TEXT',
                'constraint' => 11,
            ],
            'jenis' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'data' => [
                'type' => 'TEXT',
            ],
            'score' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'level' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => 'c2'
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('cbt_exam');

        /**
         * Cbt_answer migration table
         * @field id is cbt_room id
         * @field cbt_exam_id is id of Cbt
         * @field data is data  exam cbt, |id_of_cbt_exam,answeruser_of_cbt_exam| example 3,2|123,false|4221,pathvoice|131,pathfile
         * @field status is progress user status, example start, work, completed
         */

        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'cbt_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'question' => [
                'type' => 'TEXT',
                'constraint' => 11,
            ],
            'jenis' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'data' => [
                'type' => 'TEXT',
            ],
            'score' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'level' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => 'c2'
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('cbt_answer');


    }

    public function down()
    {
        $this->forge->dropTable('cbt_answer');
        $this->forge->dropTable("cbt_exam");
        $this->forge->dropTable("cbt_room");
        $this->forge->dropTable("cbt");
    }
}
