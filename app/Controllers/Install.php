<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Database;
use CurlHandle;

class Install extends BaseController
{
    protected $forge;
    protected $db;

    public function __construct()
    {
        $this->forge = Database::forge();
        $this->db = db_connect();
    }
    public function index($slug = null) : RedirectResponse|String
    {
        // $dbutil = \Config\Database::utils();

        $p = ["repair", "install", "update", "setup"];
        if(!in_array($slug, $p)) {
            return view("errors/404");
        }
        if($this->request->is("post") && $this->request->isAJAX()) {
            switch(isset($slug)) {
                case 'install' :
                    $this->up();
                break;
                case 'setup' :
                    $this->sesi_first();
                    $this->edu_first();
                break;
                case 'repair' :
                    $this->up(true);
                    $this->up();
                break;
                case 'update' :
                break;
            }
            return json_encode([
                "status" => "success"
            ]);
        }
        $data = array();
        $data['mysql_version'] = $this->db->getVersion();
        
        
        return view("install/" . ($slug ?? 'home'), $data);
    }

    public function up($drop = null) : String {
        try {
            $this->admin($drop);
            $this->user($drop);
            $this->sesi($drop);
            $this->events($drop);
            $this->setting($drop);
            $this->page($drop);
            $this->cbt($drop);


            return json_encode([
                "status" => "success",
                "drop" => $drop,
            ]);
        } catch (\Exception $e) {
            return json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
        
    }

    private function sesi_first() {
        $t = $this->db->table("sesi")->insert([
            "name" => date('Y') . "/" . date('Y')+1
        ], true);
        if(!$t) return false;
        $this->db->table("sesi_sub")->insert([
            [ "name" => "semester 1" ],
            [ "name" => "semester 2" ],
        ]);
        $this->db->table("setting")->insert([
            [ "name" => "sesi", "content" => 1],
            [ "name" => "sesi_sub", "content" => 1],
            [ "name" => "brand_name", "content" => "Yami Edu"],
            [ "name" => "brand_logo", "content" => false],
            [ "name" => "kementrian", "content" => "kemendikbud"],
        ]);

        return true;
    }

    public function edu_first() {
        $rules = [
            "name" => "required",
            "email" => "required",
            "password" => "required",
        ];
        if(!$this->validate($rules)) {
            return json_encode([
                "status" => "error",
                "message" => "Invalid Name or Password",
            ]);
        }
        $valid = $this->validator->getValidated();

        $db = db_connect();
        if(!$db->tableExists('edu')) return false;
        $edu = $db->table("edu");
        if($edu->countAll() > 0) return false;
        $edu->insert([
            'username' => $valid['name'],
            'email' => $valid['email'],
            'password' => password_hash($valid['password'], PASSWORD_ARGON2I),
            'role' => 1,
        ]);

    }
    private function page($drop = null) {
        if($drop) {
            $this->forge->dropTable('page', true);
            return;
        }

        $this->forge->addField([
            'id' => ['type' => 'INT','constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'slug' => ['type' => 'VARCHAR','constraint' => '255'],
            'title' => ['type' => 'VARCHAR','constraint' => '255'],
            'content' => ['type' => 'TEXT'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('page', true);
    }

    private function setting($drop = null) {
        if($drop) {
            $this->forge->dropTable('setting', true);
            return;
        }
        $this->forge->addField([
            "id" => [ "type" => "INT", 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            "name" => [ "type" => "VARCHAR"],
            "v" => [ "type" => "TEXT" ],
            "created_at" => [ "type" => "datetime" ],
            "updated_at" => [ "type" => "datetime"],
        ]);

        $this->forge->addPrimaryKey("id");
        $this->forge->addUniqueKey("name");
        $this->forge->createTable("setting", true);
    }

    private function user($drop = null) {
        if($drop) {
            $this->forge->dropTable('user', true);
            $this->forge->dropTable('user_biodata', true);
            $this->forge->dropTable('user_kelas', true);
            $this->forge->dropTable('user_sesi', true);
            return;
        }
        // Main Table
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true ],
            'username' => [ 'type' => 'VARCHAR', 'constraint' => '100' ],
            'email' => [ 'type' => 'VARCHAR', 'constraint' => '255' ],
            'password' => [ 'type' => 'VARCHAR', 'constraint' => '255' ],
            'created_at' => [ 'type' => 'DATETIME', 'null' => true ],
            'updated_at' => [ 'type' => 'DATETIME', 'null' => true ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey("username", 'username');
        $this->forge->addUniqueKey("email", 'email');
        $this->forge->createTable('user', true);

        // Secondary Table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT','unsigned' => true, 'constraint' => 5],
            'nis' => ['type' => 'VARCHAR', 'constraint' => 5],
            'nisn' => ['type' => 'VARCHAR', 'constraint' => 5],
            'fullname' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'kelas' => ['type' => 'TEXT', 'constraint' => '255'],
            'contact' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'tgl_lahir' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'tmpt_lahir' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'agama' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'gender' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'address' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'kodepos' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'hobi' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'cita' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'anak_ke' => ['type' => 'INT', 'constraint' => '11'],
            'jumlah_saudara' => ['type' => 'INT', 'constraint' => '11'],
            'sekolah_asal' => ['type' => 'VARCHAR', 'constraint' => '225'],
            'tinggi_badan' => ['type' => 'INT', 'constraint' => '11'],
            'berat_badan' => ['type' => 'INT', 'constraint' => '11'],
            'jarak_sekolah' => ['type' => 'INT', 'constraint' => '11'],
            'no_ijazah' => ['type' => 'VARCHAR', 'constraint' => '225'],
            'transportasi' => ['type' => 'INT', 'constraint' => '11'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey("user_id", false, true);
        $this->forge->addKey('id', true);
        $this->forge->addKey('nis');
        $this->forge->addKey('nisn');
        $this->forge->createTable('user_biodata', true);

        // Kelas table
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => '5', 'unsigned' => true ],
            'name' => [ 'type' => 'VARCHAR', 'constraint' => '150' ]        
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('user_kelas', true);

        // Sesi (akan ditambah tiap tahun)
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true ],
            'user_id' => [ 'type' => 'INT' ],
            'session_id' => [ 'type' => 'INT' ],
            'kelas' => [ 'type' => 'VARCHAR', 'constraint' => '150' ],
            'jabat' => [ 'type' => 'VARCHAR', 'constraint' => '150' ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('user_sesi', true);
    }

    private function sesi($drop = null) {
        if($drop) {
            $this->forge->dropTable('sesi', true);
            $this->forge->dropTable('sesi_sub', true);
            return;
        }

        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true ],
            'name' => [ 'type' => 'VARCHAR', 'constraint' => '100', 'null' => false ],
            'created_at' => [ 'type' => 'DATETIME', 'null' => true ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey("name");
        $this->forge->createTable('sesi', true);

        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true ],
            'name' => [ 'type' => 'VARCHAR', 'constraint' => '100', 'null' => false ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey("name");
        $this->forge->createTable('sesi_sub', true);
    }

    private function admin($drop = null) {
        if($drop) {
            $this->forge->dropTable('edu', true);
            $this->forge->dropTable('edu_biodata', true);
            return;
        }
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true,],
            'username' => [ 'type' => 'VARCHAR', 'constraint' => '100',],
            'alias' => [ 'type' => 'VARCHAR', 'constraint' => '255'],
            'email' => [ 'type' => 'VARCHAR', 'constraint' => '255',],
            'role' => [ 'type' => "INT", 'constraint' => 5, 'default' => 5],
            'password' => [ 'type' => 'VARCHAR', 'constraint' => '255',],
            'created_at' => [ 'type' => 'DATETIME', 'null' => true,],
            'updated_at' => [ 'type' => 'DATETIME', 'null' => true,],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey("username", 'username');
        $this->forge->addUniqueKey("email", 'email');
        $this->forge->createTable('edu', true);

        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true,],
            'edu_id' => [ 'type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'fullname' => [ 'type' => "VARCHAR", 'constraint' => 255, 'null' => true],
            'nuptk' => ['type' => "VARCHAR", 'constraint' =>100, 'null' => true],
            'tmt_date' => [ 'type' => "datetime", "null" => true],
            "tmt_sk" => [ 'type' => "VARCHAR", 'constraint' => 255, 'null' => true],
            'ijazah_id' => [ 'type' => "VARCHAR", 'constraint' => 255, 'null' => true],
            'ijazah_date' => [ 'type' => "datetime", 'null' => true]
        ]);

        $this->forge->addKey("id", true);
        $this->forge->addUniqueKey("edu_id");
        $this->forge->addUniqueKey("nuptk");
        $this->forge->addUniqueKey("tmt_sk");
        $this->forge->addUniqueKey("ijazah_id");
        $this->forge->createTable('edu_biodata', true);
    }

    private function events($drop = null) {
        if($drop) {
            $this->forge->dropTable('event', true);
            return;
        }
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'sesi_id' => ['type' => 'INT', 'constraint' => 11],
            'edu_id' => ['type' => 'INT', 'constraint' => 11],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'jenis' => ['type' => 'VARCHAR', 'constraint' => 100],
            'status' => ['type' => 'VARCHAR', 'constraint' => '255', 'default' => 'STARTING'],
            'start_date' => ['type' => 'DATETIME', 'null' => true],
            'end_date' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey("name");
        $this->forge->createTable('event', true);
    }

    private function cbt($drop = null) {
        if($drop) {
            $this->forge->dropTable('cbt', true);
            $this->forge->dropTable('cbt_room', true);
            $this->forge->dropTable('cbt_exam', true);
            $this->forge->dropTable('cbt_result', true);
            return;
        }

        // Main Table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'event_id' => ['type' => 'INT', 'constraint' => 1],
            'edu_id' => ['type' => 'INT', 'constraint' => 11],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'rooms' => ['type' => 'VARCHAR', 'constraint' => 100],
            'tag' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'start' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('event_id');
        $this->forge->createTable('cbt', true);

        // Cbt Rooms tiap events
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true,],
            'event_id' => ['type' => 'INT', 'constraint' => 11],
            'name' => ['type' => 'VARCHAR', 'constraint' => 11,],
            'alias' => ['type' => 'VARCHAR', 'constraint' => 100,],
            'users' => ['type' => 'TEXT',],
            'created_at' => ['type' => 'DATETIME', 'null' => true,],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('cbt_room', true);

        // Cbt pertanyaannya 
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'cbt_id' => ['type' => 'INT', 'constraint' => 11],
            'question' => ['type' => 'TEXT'],
            'jenis' => ['type' => 'VARCHAR', 'constraint' => 100],
            'data' => ['type' => 'TEXT'],
            'score' => ['type' => 'VARCHAR', 'constraint' => 100],
            'level' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => 'c2'],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('cbt_exam', true);

        // CBT Jawaban siswa
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true,],
            'exam_id' => ['type' => 'INT', 'constraint' => 11],
            'user_id' => ['type' => 'INT', 'constraint' => 11],
            'answer' => ['type' => 'TEXT'],
            'status' => ['type' => 'VARCHAR'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('exam_id');
        $this->forge->addKey('user_id');
        $this->forge->createTable('cbt_result', true);
    }
}
