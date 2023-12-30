<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserDetail extends Migration
{
    public function up()
    {
        /**
         * userDetails Table
         * @field id is detail user id
         * @field user_id is id of user
         * @field nis is number indux student in school
         * @field nisn is number index student nation
         * @field fullname is full name of user
         * @field kelas is class of user
         * @field 
         */
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'constraint' => 5,
            ],
            'nis' => [
                'type' => 'VARCHAR',
                'constraint' => 5,
            ],
            'nisn' => [
                'type' => 'VARCHAR',
                'constraint' => 5,
            ],
            'fullname' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'kelas' => [
                'type' => 'TEXT',
                'constraint' => '255',
            ],
            'contact' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'tgl_lahir' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],

            'tmpt_lahir' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'agama' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            
            'gender' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'kodepos' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'hobi' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'cita' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'anak_ke' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'jumlah_saudara' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'sekolah_asal' => [
                'type' => 'VARCHAR',
                'constraint' => '225',
            ],
            'tinggi_badan' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'berat_badan' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'jarak_sekolah' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'no_ijazah' => [
                'type' => 'VARCHAR',
                'constraint' => '225',
            ],
            'transportasi' => [
                'type' => 'INT',
                'constraint' => '11',
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

        $this->forge->addKey("user_id", false, true);
        $this->forge->addKey('id', true);
        $this->forge->createTable('userdetails');
    }
    
    public function down()
    {
        $this->forge->dropTable('userdetails');
        //
    }
}
