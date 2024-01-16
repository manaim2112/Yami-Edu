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

class EduController extends BaseController

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

    /**
     * Hak Akses Pengaturan Website hanya role 1 (SuperAdmin), 2 (Kepala Sekolah), dan 4.4 (Operator)
     */
    public function setting_site() {
        if(!$this->role([1, 2, 4.4])) return redirect()->back()->withCookies()->with("errors", "Maaf, anda tidak punya akses untuk melanjutkan");
        
        // Ambil sesi
        $edu = $this->db->table("sesi")->get()->getResultObject();
        return view("education_page/page/setting_site", ['setting' => setting(), 'edu' => $edu]);
    }

    public function setting_account() {
        $account = $this->db->table("edu")->where("id", auth('edu')->get('id'))->get()->getFirstRow();
        $account_biodata = $this->db->table("edu_biodata")->where("id", auth('edu')->id)->get()->getFirstRow();
        return view("education_page/page/setting_account", ['account' => $account, 'account_biodata' => $account_biodata]);
    }

    /**
     * Hak akses hanya diperuntukan oleh role 1 (SuperADmin), 2 (Kepala sekolah), 4.4 (Operator)
     */
    public function educational_staf() {

        $staf = $this->db->table("edu")->get()->getResultObject();
        $staf_category = [
            "1.0" => "Administrator",
            "2.0" => "Kepala Sekolah",
            "3.1" => "Waka Kurikulum" ,
            "3.2" => "Waka Bidang Kesiswaan",
            "3.3" => "Waka Bidang Sarana dan Prasarana",
            "4.1" => "Koordinator Tenaga Umum",
            "4.2" => "Koordinator Bimbingan Konseling",
            "4.3" => "Koordinator Laboratorium",
            "4.5" => "Koordinator Perpustakaan",
            "4.6" => "Koordinator IT",
            "5.0" => "tamu",
            "6.0" => "Pendaftar Baru",
        ];
        $role = ["SuperAdmin", "kepala " . setting()->kementrian == 'kemenag' ? 'Madrasah' : "Sekolah", "Operator", "Wakur", "Guru", "Tamu"];

        return view("education_page/page/education_staf", ['edu' => $staf, 'role' => $staf_category, "title" => "Pegawai"]);
    }

    public function learners_user($slug = null, $sub = null) {
        if($slug === 'manage_kelas') {
            $kelas = $this->db->table("user_kelas")->get()->getResultObject();
            return view("education_page/page/learning_user_kelas", ["data" => $kelas]);
        }

        if($slug === 'template_user') {
            $checkKelasIfExist = $this->db->table("user_kelas")->get()->getResultObject();
            if(sizeof($checkKelasIfExist) < 1) return redirect()->back()->withCookies()->with("errors", "Anda Belum Menambahkan kelas di tahun ini");
            $ss = new SimpleXLSXGen();
            foreach($checkKelasIfExist as $k) {
                $data = [ [""], ['<b><style border="#000000" bgcolor="#ffff00"><center><middle> KODE</middle></center> </style></b>', '<style border="#000000" bgcolor="#ffff00"></style>', '<style border="#000000" bgcolor="#ffff00"></style>', '<b><style border="#00000" height="20" bgcolor="#f8f8f8">' . $k->kode . '</style></b>'], ['<b><style border="#000000" bgcolor="#ffff00"><center><middle> KELAS</middle></center> </style></b>', '<style border="#000000" bgcolor="#ffff00"></style>', '<style border="#000000" bgcolor="#ffff00"></style>', '<b><style border="#00000" height="20" bgcolor="#f8f8f8">' . $k->name . '</style></b>'], ['<b><style border="#000000" bgcolor="#ffff00"><center><middle> Id Pegawai</middle></center> </style></b>', '<style border="#000000" bgcolor="#ffff00"></style>', '<style border="#000000" bgcolor="#ffff00"></style>', '<b><style border="#00000" height="20" bgcolor="#f8f8f8">' . $k->name . '</style></b>'], [""] ];
                array_push($data, 
                [
                    '<b><style border="#000000" height="30" bgcolor="#ffff00"><center><middle> NO</middle></center> </style></b>',
                    '<b><style border="#000000" height="30" width="100" bgcolor="#ffff00"><center><middle> NISN</middle></center> </style></b>',
                    '<b><style border="#000000" height="30" bgcolor="#ffff00"><center><middle> NIS</middle></center> </style></b>',
                    '<b><style border="#000000" height="30" bgcolor="#ffff00"><center><middle> NIK</middle></center> </style></b>',
                    '<b><style border="#000000" height="30" bgcolor="#ffff00"><center><middle> NAMA LENGKAP</middle></center> </style></b>',
                    '<b><style border="#000000" height="30" bgcolor="#ffff00"><center><middle> TEMPAT LAHIR</middle></center> </style></b>',
                    '<b><style border="#000000" height="30" bgcolor="#ffff00"><center><middle> TANGGAL LAHIR</middle></center> </style></b>',
                    '<b><style border="#000000" height="30" bgcolor="#ffff00"><center><middle> ALAMAT</middle></center> </style></b>',
                    '<b><style border="#000000" height="30" bgcolor="#ffff00"><center><middle> SANDI </middle></center> </style></b>',
                    '<b><style border="#000000" height="30" bgcolor="#ffff00"><center><middle> PHOTO </middle></center> </style></b>',
                    ], 
                );
                for($i = 0; $i < 50; $i++) {
                    array_push($data, [
                        '<style border="#000000"><center> '. $i+1 .'</center> </style>',
                        '<style border="#000000" width="100px"><center> </center> </style>',
                        '<style border="#000000"><center> </center> </style>',
                        '<style border="#000000"><center> </center> </style>',
                        '<style border="#000000"><center>  </center> </style>',
                        '<style border="#000000"><center> </center> </style>',
                        '<style border="#000000"><center> </center> </style>',
                        '<style border="#000000"><center> </center> </style>',
                        '<style border="#000000"><center> </center> </style>',
                        '<style border="#000000"><center> </center> </style>',
                    ]);
                }
                $ss->addSheet($data, $k->name)
                ->mergeCells("A2:C2")
                ->mergeCells("A3:C3")
                ->mergeCells("A4:C4")
                ->setColWidth(1, 5)
                ->setColWidth(2, 20)
                ->setColWidth(3, 20)
                ->setColWidth(4, 20);
            }
            $ss->downloadAs('template_pesertadidik.xlsx');
            // $ss->downloadAs('books_2021.xlsx');
            exit();
        }
        $user = $this->db->table("user");
        $user->select('user.id as id, user_biodata.fullname as name, user_kelas.name as kelas, user_sesi.jabat as jabat');
        $user->join("user_biodata", "user_biodata.user_id = user.id");
        $user->join("user_sesi", "user_sesi.user_id = user.id");// Mengganti "user_sesi" dengan "user_kelas"
        $user->join("user_kelas", "user_kelas.id = user_sesi.kelas_id"); 
        $user->where("user_sesi.sesi_id", setting()->sesi_id);
        $data = $user->get()->getResultObject();
        
        return view("education_page/page/learning_user", ['data' => $data]);
    }

    public function events() {
        $data = $this->db->table("event")->where("sesi_id", setting()->sesi_id)->get()->getResultObject();
        $edu = $this->db->table("edu")->select("id, alias")->get()->getResultObject();
        return view("education_page/page/events", ['data' => $data, 'edu' => $edu]);
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
        $r = auth('edu')->get('role');
        $status = false;
        foreach ($r as $ro) {
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
        $auth = auth('edu');
        if($this->request->is("get")) {
            if($any === 'logout') {
                auth("edu")->reset();
                return redirect()->to(url_to("admin.auth", 'login'))->withCookies()->with("errors", "Berhasil Keluar, terima kasih");
            }
            if($auth->has()) return redirect()->to(url_to("admin", $auth->get('username')));
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
        
        $auth->isLoggedIn = true;
        $auth->role = explode("|", $pass['role']);
        $auth->isType = 'edu';
        $auth->username = $pass['username'];
        $auth->email = $pass['email'];
        $auth->id = $pass['id'];
        $auth->set();

        return redirect()->to(url_to("edu.index", $pass['username']))->withCookies()->with("passwordValidate", ($validData['password'] == '12345') ?? false);
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
