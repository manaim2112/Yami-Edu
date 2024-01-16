<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Activity;
use App\Models\Edu;
use App\Models\Setting;
use App\Models\User;
use CodeIgniter\HTTP\RedirectResponse;
use DateTime;
use App\Libraries\SimpleXLSXGen;

use function PHPSTORM_META\type;

class EduPostController extends BaseController

{
    protected $db;
    protected $helpers = ['form'];
    public function __construct() {
        $this->db = db_connect();
    }

    /**
     * Index Edu Panel dengan mengambil data cbt, event, murid, dan guru
     * 
     */
    public function index() : String {
        $count = new \stdClass();
        $count->cbt = $this->db->table("cbt")->countAll();
        $count->event = $this->db->table("event")->countAll();
        $count->user = $this->db->table("user")->countAll();
        $count->edu = $this->db->table("edu")->countAll();
        return view("education_page/welcome", ['count' => $count]);
        
    }

    public function setting_site() {
        $rules = $this->validate([
            'kementrian' => 'required',
            'brand_name' => 'required',
            'brand_logo' => 'required',
            'sesi' => 'required',
            'sesi_sub' => 'required',
        ]);
        if(!$rules) return redirect()->back()->withCookies()->with("errors", $this->validator->getErrors());
        $valid = $this->validator->getValidated();
        $name = array_keys($valid);
        $v = array_values($valid);
        $s = new Setting();
        $sesi_id = $this->db->table("sesi")->select('id')->where("name", $valid["sesi"])->get()->getFirstRow();
        for($i=0; $i < sizeof($valid); $i++) {
            $s->where("name", $name[$i])->set('v', $v[$i])->update();
        }

        $check_id = $s->select('id')->where("name", "sesi_id")->countAllResults();
        if($check_id > 0) {
            $s->where("name", "sesi_id")->set('v', $sesi_id->id)->update();
        } else {
            $s->set("name", "sesi_id")->set('v', $sesi_id->id)->insert();
        }
        cache()->clean();
        return redirect()->back()->withCookies()->with("success", "Berhasil Diperbarui");
    }

    public function setting_account() {
        $slug = $this->request->getGet("m");
        if($slug === "default") {
            $rules = [
                "alias" => "required",
                "email" => "required",
                "password" => "required",
            ];
            if(!$this->validate($rules)) return redirect()->back()->withCookies()->with("errors", $this->validator->getErrors());
            $validData = $this->validator->getValidated();
            // Check password verify
            $user = $this->db->table("edu")->select("password")->where("id", auth("edu")->get("id"))->get()->getFirstRow();
            if($user === null) return redirect()->back()->withCookies()->with("errors", "Tidak ada data tentang anda, silahkan login kembali");
            if(!password_verify($validData['password'], $user->password)) return redirect()->back()->withCookies()->with("errors", ["Sandi Konfirmasi" => " Sandi Yang anda masukkan salah"]);
            if($this->db->table("user")->set("alias", $validData['alias'])
                ->set("email", $validData['email'])->where("id", auth('edu')->id)->update()) {
                    return redirect()->back()->withCookies()->with("success", "Berhasil Di perbarui data anda");
            }

            return redirect()->back()->withCookies()->with("errors", "Gagal memperbarui data anda : " . $this->db->error());
        }


        if(!$this->validate([
                        'fullname' => 'required',
                        'nuptk' => 'required',
                        'tmt-date' => 'required',
                        'tmt-sk' => 'required',
                        'ijazah-date' => 'required',
                        'ijazah-id' => 'required',
        ])) return redirect()->back()->withCookies()->with("errors", $this->validator->getErrors());
        $valid = $this->validator->getValidated();
        $edu_bio = $this->db->table("edu_biodata");
        $id = auth("edu")->id;
        $count = $edu_bio->where("edu_id", $id)->selectCount("id", "count")->get()->getFirstRow();
        if($count->count === 1) {
            $edu_bio->where("edu_id", $id)->ignore(true)->update([
                "fullname" => $valid['fullname'],
                'nuptk' => $valid['nuptk'],
                "tmt_date" => $valid['tmt-date'],
                "tmt_sk" => $valid['tmt-sk'],
                "ijazah_date" => $valid['ijazah-date'],
                "ijazah_id" => $valid['ijazah-id'],
            ]);
        } else {
            $edu_bio->ignore(true)->insert([
                "edu_id" => $id,
                "fullname" => $valid['fullname'],
                'nuptk' => $valid['nuptk'],
                "tmt_date" => $valid['tmt-date'],
                "tmt_sk" => $valid['tmt-sk'],
                "ijazah_date" => $valid['ijazah-date'],
                "ijazah_id" => $valid['ijazah-id'],
            ]);
        }
        return redirect()->back()->withCookies()->with("success", "berhasil diperbarui");
    }

    public function educational_staf($slug) {
        $rules = [
            "nuptk" => "required",
            "email" => "required",
            "fullname" => "required",
            "jabatan" => "required",
        ];

        if(!$this->validate($rules)) return redirect()->back()->withCookies()->with("errors", $this->validator->getErrors());
        $valid = $this->validator->getValidated();
        $staf = $this->db->table("edu")
            ->set("username", $valid['nuptk'])
            ->set("alias", $valid['fullname'])
            ->set("email", $valid['email'])
            ->set('role', implode("|", $valid['jabatan']))->ignore();
        if($slug === "create") {
            if($staf->insert()) {
                return redirect()->back()->withCookies()->with("success", "Berhasil ". $slug . " Pegawai");
            }
            return redirect()->back()->withCookies()->with("errors", $this->db->error());

        } elseif($slug === 'edit') {
            $id = $this->request->getVar("id");
            if(!$id) return redirect()->back()->withCookies()->with("errors", "Pastikan di isi dengan benar");
             
            if($staf->where("id", $id)->update()) {
                return redirect()->back()->withCookies()->with("success", "Berhasil ". $slug . " Pegawai");
            }
            return redirect()->back()->withCookies()->with("errors", $this->db->error());
        }
        return redirect()->back()->withCookies()->with("errors", "Terjadi kesalahan, hubungi administrator");

    }

    public function events($slug) {
        if(!isset($slug)) return redirect()->back()->withCookies()->with("errors", "Maaf, anda tidak diperbolehkan akses");
        $rules = array();
        switch($slug) {
            case "create" : 
                $rules = [
                    "tanggal" => "required",
                    "tanggal_end" => "required",
                    "name" => "required",
                    "type" => "required",
                    "ketua" => "required"
                ];
                
            break;
            case "edit" :
                    $rules = [
                        "id" => "required",
                        "tanggal_edit" => "required",
                        "status_edit" => "required",
                        "tanggal_end_edit" => "required",
                        "name_edit" => "required",
                        "ketua_edit" => "required"
                    ];
            break;
            case "delete" :
                $rules = ["id" => "required"];
        }
        if(!$this->validate($rules)) return redirect()->back()->withCookies()->with("errors", $this->validator->getErrors());
        $valid = $this->validator->getValidated();
        $event = $this->db->table("event")->ignore();
        switch($slug) {
            case 'create' :
                $event->set("sesi_id", setting()->sesi_id);
                $event->set("edu_id", $valid['ketua']);
                $event->set("name", $valid['name']);
                $event->set("jenis", $valid['type']);
                $event->set("status", "WAITING");
                $event->set("start_date", $valid['tanggal']);
                $event->set("end_date", $valid['tanggal_end']);
                $event->set("created_at", date("Y-m-d H:i:s"));
                $event->set("updated_at", date("Y-m-d H:i:s"));
                $response = $event->insert();
            break;
            case 'edit' :
                $event->set("edu_id", $valid['ketua_edit']);
                $event->set("name", $valid['name_edit']);
                $event->set("status", $valid['status_edit']);
                $event->set("start_date", $valid['tanggal_edit']);
                $event->set("end_date", $valid['tanggal_end_edit']);
                $event->set("updated_at", date("Y-m-d H:i:s"));
                $response = $event->where("id", $valid['id'])->update();                
            break;
            case 'delete' :
                $response = $event->where("id", $valid['id'])->delete();
        }

        if(!$response) return redirect()->back()->withCookies()->with("errors", $this->db->error());

        return redirect()->back()->withCookies()->with("success", "Berhasil melakukan $slug kegiatan");
    }

    public function learners_user($slug = null, $sub = null) {
        if($slug === "upload") {

        }

        if($slug === "manage_kelas") {
            $rules = [
                "kode" => "required",
                "name" => "required",
            ];
            if($sub === "create") {
                if(!$this->validate($rules)) return redirect()->back()->withCookies()->with("errors", $this->validator->getErrors());
                $valid = $this->validator->getValidated();
                $insert = $this->db->table("user_kelas")->ignore()
                    ->set("kode", esc($valid['kode']))
                    ->set("name", esc($valid['name']))
                    ->insert();
                if(!$insert) return redirect()->back()->withCookies()->with("errors", $this->db->error());

                return redirect()->back()->withCookies()->with("success", "Berhasil Menambahkan kelas ". $valid['name']);
            }
        }
        $user = $this->db->table("user");
        // menghubungkan 3 tabel, tabel user, user_biodata, user_sesi dimana diambil sesuai sesi yang terbaru
        $user->select('user.id, user_biodata.fullname, user_kelas.name');
        $user->join("user_biodata", "user_biodata.user_id = user.id");
        $user->join("user_sesi", "user_sesi.user_id = user.id");
        $user->where("user_sesi.sesi", setting()->sesi);
        $data = $user->get()->getResultObject();
        return view("education_page/learning_user", ['data' => $data]);
    }
    public function learning_user_detail(String $id) : String {
        $biodata = $this->db->table("user_biodata")->where("user_id", $id)->get()->getFirstRow();
        $sesi = $this->db->table("user_sesi")->where("user_id", $id)->get()->getFirstRow();

        return view("education_page/learning_user_detail", ['biodata' => $biodata, 'sesi' => $sesi]);
    }

    
    /**
     * Computer Based Learning di bawah naungan dari event, ketika event dibuat untuk ujian, maka otomatis akan terdeteksi di cbt
     * @var $idEvent adalah id dari event yang berlansung
     */
    public function computer_based_learning(String $idEvent = null, String $idCbt = null) {
        // check apakah ada event di tahun ini
        $result = $this->db->table("cbt")
            ->where("sesi_id", setting()->sesi_id)
            ->where("jenis", "cbt")
            ->get()->getResultObject();

        return view("education_page/computer_based_learning", ['data' => $result]);
    }



    /**
     * Role Education Staf
     * @string 1.0 menyatakan superadmin
     * @string 2.0 menyatakan kepala
     * @string 3.1 menyatakan waka kurikulum
     * @string 3.2 menyatakan waka bidang kesiswaan
     * @string 3.3 menyatakan waka bidang sarana dan prasarana
     * @string 3.4 menyatakan waka bidang kehumasan
     * @string 4.1 menyatakan Koordinator Tenaga Umum
     * @string 4.2 menyatakan koordinator Bimbingan Konseling
     * @string 4.3 menyatakan koordinator Laboratorium
     * @string 4.5 menyatakan koordinator Perpustakaan
     * @string 4.6 menyatakan koordinator IT
     * @string 5.0 menyatakan tamu 
     * @string 6.0 menyatakan perndaftar baru tidak terdeteksi
     * @param array([...float]) $role - example : [3.1,4.2]
     */

    private function role(array $role) {
        $r = auth('edu')->role;
        $rArray = explode("|", $r);
        $status = false;
        foreach ($rArray as $ro) {
            if(in_array((float)$ro, $role)) {
                $status = true;
                break;
            }
        }
        return $status;
    }



    /**
     * Controller Authentication for User Management
     * @param String $any optional match for 'login' or 'registration'
     * @return String
     */
    public function auth(String $any = null) : String|RedirectResponse|null
    {
        if(!isset($any)) return redirect()->to(url_to("admin.auth", "login"));

        if(!in_array($any, ['login', 'register', 'logout'])) {
            return "404 Not FOund";
        }
        
        $session = session();
        if($this->request->is("get")) {
            if($any === 'logout') {
                $session->remove("admin_isLoggedIn");
                $session->remove("admin_username");
                $session->remove("admin_role");
                $session->remove("admin_email");
                $session->remove("admin_id");
               
                
                return redirect()->to(url_to("admin.auth", 'login'))->withCookies()->with("errors", "Berhasil Keluar, terima kasih");
            }
            if($this->getAuth('name') !== null) return redirect()->to(url_to("admin", $this->getAuth('name')));
            return view("admin/auth", ["lOrR" => $any]);
        }

        if($any === 'login') {
            $rules = [
                "username" => "required",
                "password" => "required",
            ];
        } else {
            $rules = [
                "username" => "required",
                "email" => "required",
                "password" => "required",
            ];
        }
        if(!$this->validate($rules)) return view("admin/auth", ["errors" => $this->validator->getErrors(), "lOrR" => $any]);
        
        $validData = $this->validator->getValidated();
        $user = new Edu();
        
        if($any === 'register') {
            $validData['password'] = password_hash($validData['password'], PASSWORD_ARGON2I);
            $user->insert($validData);
            return redirect()->to(route_to('admin/auth', 'login'))->withCookies()->with("success", "Registration Success");
        }
        
        
        $pass = $user->orWhere("username", $validData["username"])->orWhere("email", $validData["username"])->first();

        if(!isset($pass)) return redirect()->to(url_to('admin.auth', 'login'))->withCookies()->with("errors", "Sorry, Kamu Belum terdaftar. silahkan melakukan pendaftaran terlebih dahulu");
        if(!password_verify($validData['password'], $pass['password'])) return redirect()->to(url_to('admin.auth', 'login'))->withCookies()->with("errors", "Sorry, sandi anda salah");
        
        $session->set("admin_isLoggedIn", true);
        $session->set("admin_username",$pass['username']);
        $session->set("admin_role", $pass['role']);
        $session->set("admin_email",$pass['email']);
        $session->set("admin_id",$pass['id']);

        return redirect()->to(url_to("admin", $pass['username']))->withCookies()->with("passwordValidate", ($validData['password'] == '12345') ?? false);
    }

    protected function getAuth(String $name = null) : String|null {
        try {

            return service('request')->admin->{$name};
            //code...
        } catch (\Throwable $th) {
            return null;
        }

        return null;
    }
}
