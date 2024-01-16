<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Sesi;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Database;

class Setup extends BaseController
{
    protected $forge;
    protected $db;
    protected $errors = array();

    public function __construct()
    {
        $this->forge = Database::forge();
        $this->db = db_connect();
    }
    public function index($slug = null) : RedirectResponse|String
    {
        // $dbutil = \Config\Database::utils();

        if($this->request->is("post") && $this->request->isAJAX()) {
            if($slug == 'install') {
                $this->up(false);
            } else if($slug == 'first') {
                if(!$this->sesi_first()) return json_encode([
                    "status" => "error",
                    "message" => "sesi yang terbaru sudah terbuat"
                ]);
                if(!$this->edu_first()) return json_encode([
                    "status" => "error",
                    "message" => "akun admin sudah terbuat"
                ]);
            } else if($slug == 'repair') {
                $this->up(true);
                $this->up(false);
            } else if($slug == 'update') {
                
            }

            return json_encode([
                "status" => "success"
            ]);            
        }
        $data = array();
        $data['mysql_version'] = $this->db->getVersion();
        
        return view("setup/" . ($slug ?? 'home'), $data);
    }

    public function up($drop = false) : String {
        try {
            $this->sesi($drop);
            $this->setting($drop);
            $this->events($drop);
            $this->page($drop);
            $this->admin($drop);
            $this->user($drop);
            $this->cbt($drop);

            setting(true);
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

    /**
     * memasukkan data penting dalam database, meliputi tahun pelajaran, semester, dan juga pengaturan default
     */
    private function sesi_first() : bool {
        $insertSesi = $this->db->table("sesi")->ignore(true)->insertBatch([
            [ "name" => date('Y')-1 . "/" . date('Y'), "created_at" => date('Y-m-d H:i:s')],
            [ "name" => date('Y') . "/" . date('Y')+1, "created_at" => date('Y-m-d H:i:s')],
        ]);

        $this->db->table("sesi_sub")->ignore(true)->insertBatch([
            [ "name" => "semester 1" ],
            [ "name" => "semester 2" ],
        ]);
        $this->db->table("setting")->ignore(true)->insertBatch([
            [ "name" => "installed", "v" => "yes"],
            [ "name" => "sesi_id", "v" => $insertSesi[0] ?? '1'],
            [ "name" => "sesi", "v" => date('Y') . "/" . date('Y')+1],
            [ "name" => "sesi_sub", "v" => "1"],
            [ "name" => "brand_name", "v" => "Yami Edu"],
            [ "name" => "brand_logo", "v" => null],
            [ "name" => "kementrian", "v" => "kemendikbud"],
            [ 'name' => "themes", "v" => "default"],
            [ "name" => "status_cbt", "v" => NULL],
            [ "name" => "status_kinerja", "v" => NULL],
            [ "name" => "status_password", "v" => NULL],
            [ "name" => "status_onandroid", "v" => NULL],
            [ "name" => "status_onwindow", "v" => NULL],
            [ "name" => "status_onmac", "v" => NULL],
            [ "name" => "status_onapi", "v" => NULL],
        ]);

        return true;
    }

    /**
     * Menambahkan Admin Pertama default
     */
    public function edu_first() : bool {
        $edu = $this->db->table("edu")->ignore(true);
        if($edu->countAll() > 0) return false;
        $edu->insert([
            'username' => 'admin',
            'alias' => "Administrator",
            'email' => 'admin@mail.com',
            'password' => password_hash('12345', PASSWORD_ARGON2I),
            'role' => "1.0",
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        // $edu_sesi = $this->db->table("edu_sesi")->ignore(true)
        //     ->set("edu_id", $this->db->insertID())
        //     ->set("sesi_id", 1)
        //     ->set("role", 1);

        return false;
    }
    private function page($drop = false) {
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
    private function dropTable(array $strings) {
        foreach ($strings as $s) {
            $this->forge->dropTable($s, true);
        }
    }

    /**
     * Penilaian ada table nilai dan tipe nilai
     * @category nilai - merujuk semua nilai peserta didik
     * @category nilai_type - merujuk jenis penilaian, contoh PH, Sosial, Adab, p5RA, dll.
     */
    private function penilaian($drop = false) {
        if($drop) {
            $this->dropTable(['nilai', 'nilai_type']);
            return;
        }

        $this->forge->addField([
            "id" => [ "type" => "INT", 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            "alias" => ["type" => "VARCHAR", 'constraint' =>50, 'unsigned' => true],
            "name" => [ "type" => "VARCHAR", 'constraint' => 100],
            "category" => [ "type" => "VARCHAR", 'constraint' => 225, 'null' => true],
            "keterangan" => [ "type" => "VARCHAR", 'constraint' => 225, 'null' => true],
        ]);
        $this->forge->addKey("id", true);
        $this->forge->createTable("nilai_type", true);


        $this->forge->addField([
            "id" => [ "type" => "INT", 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            "v_type" => ["type" => "INT", 'constraint' =>5, 'unsigned' => true],
            "v" => [ "type" => "INT", 'constraint' => 11],
            "extra" => [ "type" => "VARCHAR", 'constraint' => 225, 'null' => true],
        ]);

        $this->forge->addKey("id", true);
        $this->forge->addForeignKey("v_type", "nilai_type", "id");
        $this->forge->createTable("nilai", true);
    }

    private function setting($drop = false) {
        if($drop) {
            $this->forge->dropTable('setting', true);
            return;
        }
        $this->forge->addField([
            "id" => [ "type" => "INT", 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            "name" => [ "type" => "VARCHAR", 'constraint' => 225],
            "v" => [ "type" => "TEXT" ]
        ]);

        $this->forge->addPrimaryKey("id");
        $this->forge->addUniqueKey("name");
        $this->forge->createTable("setting", true);
    }

    private function user($drop = false) {
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
            'alias' => ['type' => 'VARCHAR', 'constraint' => 255],
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
            'phone' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'tgl_lahir' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'tmpt_lahir' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'agama' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'gender' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'address' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'kodepos' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'hobi' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'kelamin' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'cita' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'anak_ke' => ['type' => 'INT', 'constraint' => '11', 'null' => true],
            'jumlah_saudara' => ['type' => 'INT', 'constraint' => '11', 'null' => true],
            'sekolah_asal' => ['type' => 'VARCHAR', 'constraint' => '225', 'null' => true],
            'tinggi_badan' => ['type' => 'INT', 'constraint' => '11', 'null' => true],
            'berat_badan' => ['type' => 'INT', 'constraint' => '11', 'null' => true],
            'disabilitas' => ['type' => 'bool', 'default' => false],
            'jarak_sekolah' => ['type' => 'INT', 'constraint' => '11', 'null' => true],
            'no_ijazah' => ['type' => 'VARCHAR', 'constraint' => '225', 'null' => true],
            'transportasi' => ['type' => 'INT', 'constraint' => '11', 'null' => true],
            'ayah_name' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'ayah_pekerjaan' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'ayah_penghasilan' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'ayah_status' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'ayah_pendidikan' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'ayah_ktp' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'ibu_name' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'ibu_pekerjaan' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'ibu_penghasilan' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'ibu_status' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'ibu_pendidikan' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'ibu_ktp' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'kk' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
        ]);
        $this->forge->addKey("user_id", false, true);
        $this->forge->addKey('id', true);
        $this->forge->addKey('nis');
        $this->forge->addKey('nisn');
        $this->forge->addForeignKey("user_id", "user", "id");
        $this->forge->createTable('user_biodata', true);

        $this->forge->addField([
            'id' => ['type' => "INT", 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => "INT", 'constraint' => 5, 'unsigned' => true],
            'kk' => ['type' => "VARCHAR", 'constraint' => 255, 'null' => true],
            'pip' => ['type' => "VARCHAR", 'constraint' => 255, 'null' => true],
            'ktp_ayah' => ['type' => "VARCHAR", 'constraint' => 255, 'null' => true],
            'ktp_ibu' => ['type' => "VARCHAR", 'constraint' => 255, 'null' => true],
            'ijazah' => ['type' => "VARCHAR", 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'user', 'id');
        $this->forge->createTable("user_img", true);
        // Kelas table
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => '5', 'unsigned' => true, 'auto_increment' => true ],
            'kode' => [ 'type' => 'VARCHAR', 'constraint' => '30' ],
            'name' => [ 'type' => 'VARCHAR', 'constraint' => '100' ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('user_kelas', true);

        // Sesi (akan ditambah tiap tahun)
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true ],
            'user_id' => [ 'type' => 'INT', 'constraint' => 5, 'unsigned' => true ],
            'kelas_id' => [ 'type' => 'INT','constraint' => '5', 'unsigned' => true ],
            'sesi_id' => [ 'type' => 'INT', 'constraint' => 5, 'unsigned' => true ],
            'jabat' => [ 'type' => 'VARCHAR', 'constraint' => '150' ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('kelas_id', 'user_kelas', 'id');
        $this->forge->addForeignKey('sesi_id', 'sesi', 'id');
        $this->forge->addForeignKey('user_id', 'user', 'id');
        $this->forge->createTable('user_sesi', true);
    }

    private function sesi($drop = false) {
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

        // Sesi Sub berfungsi nanti untuk penilaian siswa disetiap semester, dan juga kegiatan pada setiap semester
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true ],
            'name' => [ 'type' => 'VARCHAR', 'constraint' => '100', 'null' => false ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey("name");
        $this->forge->createTable('sesi_sub', true);
    }

    private function admin($drop = false) {
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
            'role' => [ 'type' => "VARCHAR", 'constraint' => 30, 'default' => '6'],
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
        $this->forge->addForeignKey('edu_id', 'edu', 'id');
        $this->forge->addUniqueKey("nuptk");
        $this->forge->addUniqueKey("tmt_sk");
        $this->forge->addUniqueKey("ijazah_id");
        $this->forge->createTable('edu_biodata', true);



    }

    private function events($drop = false) {
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

    private function cbt($drop = false) {
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
            'status' => ['type' => 'VARCHAR', 'constraint' => 100],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('exam_id');
        $this->forge->addKey('user_id');
        $this->forge->createTable('cbt_result', true);
    }
}
