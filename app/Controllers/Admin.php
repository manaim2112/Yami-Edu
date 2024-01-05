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


class Admin extends BaseController
{
    protected $pathname = ['users', 'teams', 'cbt', 'raport', 'settings', 'setting_site', 'history', 'value', 'events'];
    protected $SUBPAGE = ['create', 'edit', 'delete'];
    protected $helpers = ['form'];

    public function index(String $username = null, String $page = null, String $subpage = null) : String|RedirectResponse|null
    {
        if($this->request->is("post")) return $this->post($username, $page, $subpage);

        $data = array();
        if(!$username) return redirect()->to(url_to("admin", $this->getAuth('name')));
        $db = db_connect();
        // Checking Setting On cache;
        if(!$newSetting = cache('setting')) {
            $settings = $db->table("setting")->select('name, v')->get()->getResultObject();
            $newSetting = new \stdClass();
            foreach($settings as $setting) {
                $newSetting->{$setting->name} = $setting->v;
            }
            cache()->save("setting", $newSetting);
        }

        $auth = service('request')->admin;
        if(!$page) return view("admin/home", ['auth' => $auth, 'setting' => cache('setting')]);
       
        if(!in_array($page, $this->pathname)) return view('errors/404');

        switch($page) {
            case 'cbt' :
                $session = $db->table('session')->select('id, name, current')->get()->getResult();
                foreach ($session as $value) {
                    if($value->current) {
                        $currentId = $value->id;
                    }
                }

                if(in_array($this->getAuth('role'), [1,2,3])) {
                    $data = $db->table('activities')->orWhere("jenis", "cbt");
                    if(isset($currentId)) {
                        $data->orWhere("session_id", $currentId);
                    }
                    $data = $data->get()->getResult();
                } else if (in_array($this->getAuth('role'), [4])) {
                    $builder = $db->table('activities_teams');
                    $selected = $builder->select('*');
                    $selected->join("activities_teams", 'activities_teams.activity_id = activities.id');
                    $selected->where("activities_teams.admin_id", $this->getAuth('id'));
                    $selected->where("activities.jenis", "cbt");
                    if(isset($currentId)) {
                        $selected->where("activities.activity_id", $currentId);
                    }
                    $data = $selected->get()->getResult();
                } else {
                    $data = array();
                }
                if($subpage) {
                    $SUBPAGE = ['create', 'edit', 'delete'];
                    if(!in_array($subpage, $SUBPAGE)) return view("errors/404");
                }
            break;
            case 'events':
                if($subpage) {
                    $SUBPAGE = ['create', 'edit', 'delete', 'kelas'];
                    if(!in_array($subpage, $SUBPAGE)) return view("errors/404");
                    if($subpage === 'edit') {
                        $data = new Activity();
                        $data = $data->find($this->request->getGet('id'));
                    }

                } else {
                    $data = new Activity();
                    
                }
            break;
            case 'users' :
                $kelas = $db->table("user_kelas")->select()->where("sesi_id", cache("setting")->sesi_id)->get()->getResultObject();
                if($subpage) {
                    if($subpage === 'template_user') {
                        // require_once "Plugins/PHPExcel.php";
                        $checkKelasIfExist = $db->table("user_kelas")->where("sesi_id", cache("setting")->sesi_id)->get()->getResultObject();
                        if(sizeof($checkKelasIfExist) < 1) return redirect()->back()->withCookies()->with("errors", "Anda Belum Menambahkan kelas di tahun ini");
                        $ss = new SimpleXLSXGen();
                        
                        // $data = [
                        //     ['Integer', 123],
                        //     ['Float', 12.35],
                        //     ['Percent', '12%'],
                        //     ['Currency $', '$500.67'],
                        //     ['Currency €', '200 €'],
                        //     ['Currency ₽', '1200.30 ₽'],
                        //     ['Currency (other)', '<style nf="&quot;£&quot;#,##0.00">500</style>'],
                        //     ['Currency Float (other)', '<style nf="#,##0.00\ [$£-1];[Red]#,##0.00\ [$£-1]">500.250</style>'],
                        //     ['Datetime', '2020-05-20 02:38:00'],
                        //     ['Date', '2020-05-20'],
                        //     ['Time', '02:38:00'],
                        //     ['Datetime PHP', new DateTime('2021-02-06 21:07:00')],
                        //     ['String', 'Very long UTF-8 string in autoresized column'],
                        //     ['Formula', '<f v="135.35">SUM(B1:B2)</f>'],
                        //     ['Hyperlink', 'https://github.com/shuchkin/simplexlsxgen'],
                        //     ['Hyperlink + Anchor', '<a href="https://github.com/shuchkin/simplexlsxgen">SimpleXLSXGen</a>'],
                        //     ['Internal link', '<a href="sheet2!A1">Go to second page</a>'],
                        //     ['RAW string', "\0" . '2020-10-04 16:02:00']
                        // ];
                        // array_push($data, 
                        //     ['Normal', '12345.67'],
                        //     ['Bold', '<b>12345.67</b>'],
                        //     ['Italic', '<i>12345.67</i>'],
                        //     ['Underline', '<u>12345.67</u>'],
                        //     ['Strike', '<s>12345.67</s>'],
                        //     ['Bold + Italic', '<b><i>12345.67</i></b>'],
                        //     ['Hyperlink', 'https://github.com/shuchkin/simplexlsxgen'],
                        //     ['Italic + Hyperlink + Anchor', '<i><a href="https://github.com/shuchkin/simplexlsxgen">SimpleXLSXGen</a></i>'],
                        //     ['Green', '<style color="#00FF00">12345.67</style>'],
                        //     ['Bold Red Text', '<b><style color="#FF0000">12345.67</style></b>'],
                        //     ['Size 32 Font', '<style font-size="32">Big Text</style>'],
                        //     ['Blue Text and Yellow Fill', '<style bgcolor="#FFFF00" color="#0000FF">12345.67</style>'],
                        //     ['Border color', '<style border="#000000">Black Thin Border</style>'],
                        //     ['<top>Border style</top>','<style border="medium"><wraptext>none, thin, medium, dashed, dotted, thick, double, hair, mediumDashed, dashDot,mediumDashDot, dashDotDot, mediumDashDotDot, slantDashDot</wraptext></style>'],
                        //     ['Border sides', '<style border="none dotted#0000FF medium#FF0000 double">Top No + Right Dotted + Bottom medium + Left double</style>'],
                        //     ['Left', '<left>12345.67</left>'],
                        //     ['Center', '<center>12345.67</center>'],
                        //     ['Right', '<right>Right Text</right>'],
                        //     ['Center + Bold', '<center><b>Name</b></center>'],
                        //     ['Row height', '<style height="50">Row Height = 50</style>'],
                        //     ['Top', '<style height="50"><top>Top</top></style>'],
                        //     ['Middle + Center', '<style height="50"><middle><center>Middle + Center</center></middle></style>'],
                        //     ['Bottom + Right', '<style height="50"><bottom><right>Bottom + Right</right></bottom></style>'],
                        //     ['<center>MERGE CELLS MERGE CELLS MERGE CELLS MERGE CELLS MERGE CELLS</center>', null],
                        //     ['<top>Word wrap</top>', "<wraptext>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book</wraptext>"],
                        //     ['Linebreaks', "Line 1\nLine 2\nLine 3"]
                        // );
                        foreach($checkKelasIfExist as $k) {
                            $data = [
                                [""],
                                [
                                    '<b><style border="#000000" bgcolor="#ffff00"><center><middle> KELAS</middle></center> </style></b>', '<style border="#000000" bgcolor="#ffff00"></style>', '<style border="#000000" bgcolor="#ffff00"></style>', '<b><style border="#00000" height="20" bgcolor="#f8f8f8">' . $k->name . '</style></b>'
                                ],
                                [""]
                            ];
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
                            $ss->addSheet($data, $k->name)->mergeCells("A2:C2")
                            ->setColWidth(1, 5)
                            ->setColWidth(2, 20)
                            ->setColWidth(3, 20)
                            ->setColWidth(4, 20);
                        }
                        $ss->downloadAs('template_pesertadidik.xlsx');
                        // $ss->downloadAs('books_2021.xlsx');
                        exit();
                        // $xlsx = new PHPExcel();
                        // $xlsx->setActiveSheetIndexByName("kelas")
                        //     ->setCellValue("A1", "No")
                        //     ->setCellValue("B1", "NISN")
                        //     ->setCellValue("C1", "NIS")
                        //     ->setCellValue("E1", "NIK")
                        //     ->setCellValue("F1", "TEMPAT LAHIR")
                        //     ->setCellValue("G1", "TANGGAL LAHIR (dd-mm-yyyy)")
                        //     ->setCellValue("H1", "ALAMAT");
                        // $writer = PHPExcel_IOFactory::createWriter($xlsx, 'Excel2007');
                        // $filename = "Data Siswa (RINGAN)";
                        // // Redirect hasil generate xlsx ke web client
                        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                        // header('Content-Disposition: attachment;filename='.$filename.'.xlsx');
                        // header('Cache-Control: max-age=0');
                        
                        // $writer->save('php://output');
                    } else if($subpage === 'kelas') {
                        $data['guru'] = $db->table("edu")->select("id, alias")->get()->getResultObject();
                        $data['kelas'] = $kelas;
                    }
                } else {
                    // $user = $db->table("user");
                    $k = $this->request->getGet("kelas");
                    $user = new User();
                    $user->select('user.id as id, user_biodata.fullname as fullname, user_kelas.name as kelas, user_sesi.jabat as jabat');
                    $user->join('user_sesi', 'user_sesi.user_id = user.id', 'left');
                    $user->join('user_biodata', 'user_biodata.user_id = user.id', 'left');
                    $user->join('user_kelas', 'user_kelas.name = user_sesi.kelas', 'left');
                    // if($k === 'none') {
                    //     $user->where('user_sesi.kelas', '=', NULL);
                    // } elseif($k) {
                    //     $user->where('user_kelas.name', '=', esc($k));
                    // }
                    $data['user'] = $user->findAll();
                    $data['kelas'] = $kelas;
                }
        }
        // $this->cachePage(60);
        return view("admin/page/" . $page, ['data' => $data ?? [], 'subpage' => $subpage, 'setting' => cache('setting'), 'auth' => $auth]);
    }

    public function post(String $username = null, String $page = null, String $subpage = null) : String|RedirectResponse|null
    {
        if(!$username || $username !== $this->getAuth('name')) return redirect()->back()->withCookies()->with("errors", "Tindakan ilegal tidak diperbolehkan");
        if(!in_array($page, $this->pathname)) return redirect()->back()->withCookies()->with("errors", "Permintaan data anda tidak ditemukan");
        $id = session()->get("admin_id");
        $db = db_connect();

        switch($page) {
            case 'users' : 
                if($subpage) {
                    if($subpage == 'kelas') {
                        $this->validate([
                            "kelas" => "required",
                            "walikelas" => "required",
                        ]);
                        $kelas = esc($this->request->getVar("kelas"));
                        $pattern = '/^(\d+)-([A-Z]+)(?:_(\d+))?$/';

                        if(!preg_match($pattern,$kelas)) return redirect()->back()->withCookies()->with("errors", "HMM, pastikan format kelas benar, contoh : 7-G, 7 menyatakan kelas dan G menyatakan bagian ruang, atau jika kejuruan bisa dengan 10-MIPA_3");
                        $edu_id = $this->request->getVar("walikelas");
                        $sesi_id = cache("setting")->sesi_id;
                        $check = $db->table("user_kelas")
                            ->where("name", "=", $kelas)
                            ->where("sesi_id", "=", $sesi_id)
                            ->orWhere("edu_id", "=", $edu_id)
                            ->where("sesi_id", "=", $sesi_id)
                            ->countAll();
                        if($check > 0) return redirect()->back()->withCookies()->with("errors", "HMM, Sepertinya kelas sudah dibuat atau wali kelas sudah ditentukan, coba lagi");

                        $db->table("user_kelas")
                            ->insert([
                                "name" => $kelas,
                                "sesi_id" => $sesi_id,
                                "edu_id" => $edu_id,
                            ]);

                        return redirect()->back()->withCookies()->with("success", "Berhasil menambahkan kelas");
                    } elseif($subpage == 'upload') {
                        $rules = [
                            "kelas" => "required",
                            "nisn" => "required",
                            "nis" => "required",
                            "fullname" => "required",
                            "password" => "required",
                        ];
                        if(!$this->validate($rules)) return json_encode(["status" => "errors", "message" => json_encode(validation_errors())]);
                        $valid = $this->request->getJsonVar();

                        $user = new User();
                        $id = $user->insert([
                            "username" => $valid->nisn,
                            "alias" => $valid->fullname,
                            "email" => $valid->nisn . '@mail.com',
                            "password" => password_hash($valid->password ?? '12345', PASSWORD_DEFAULT)
                        ], true);

                        if(!$id) return json_encode(["status" => "errors", "message" => "Data Ganda"]);

                        $db->table('user_biodata')->ignore(true)->insert([
                            "user_id" => $id,
                            "nis" => $valid->nis,
                            "nisn" => $valid->nisn,
                            "fullname" => $valid->fullname,
                            "tgl_lahir" => $valid->date_birth ?? "",
                            "tmpt_lahir" => $valid->city_born ?? "",
                            "address" => esc($valid->address ?? ""),
                        ]);
                        $db->table('user_sesi')->ignore(true)->insert([
                            "user_id" => $id,
                            "sesi" => cache("setting")->sesi_id,
                            "kelas" => $valid->kelas,
                        ]);

                        return json_encode(["status" => "success"]);

                    }
                }
            break;
            case 'setting_site' :
                $rules = $this->validate([
                    'kementrian' => 'required',
                    'brand_name' => 'required',
                    'brand_logo' => 'required',
                    'sesi' => 'required',
                    'sesi_sub' => 'required',
                ]);
                if(!$rules) return redirect()->back()->withCookies()->with("errors", json_encode($this->validator->getErrors()));
                $valid = $this->validator->getValidated();
                $name = array_keys($valid);
                $v = array_values($valid);
                $s = new Setting();
                $sesi_id = $db->table("sesi")->select('id')->where("name", $valid["sesi"])->get()->getFirstRow();
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
            case 'settings' :
                if($subpage == 'account') {
                    if(!$this->validate([
                        'alias' => 'required',
                        'email' => 'required',
                        'password_confirm' => 'required',
                    ])) return redirect()->back()->withCookies()->with("errors", "Invalid To update");
                    $valid = $this->validator->getValidated();
                    try {
                        $client = \Config\Services::curlrequest([
                            'baseURI' => 'https://edu.yami.my.id/token/v1/',
                        ]);
                        $key = $client->get("keyVerify")->getJSON();
                        $keydecode = json_validate($key) ? json_decode($key, true) : false;
                        if(!$keydecode) return redirect()->back()->withCookies()->with("errors", "Pastikan kode token sudah tepat");
                        if($keydecode['token'] !== $valid['password_confirm']) return redirect()->back()->withCookies()->with("errors", "Pastikan kode token sudah tepat");
                        $edu = new Edu();
                        $edu->update($id, [
                            'alias' => $valid['alias'],
                            'email' => $valid['email']
                        ]);
                        cache("setting")->clean();
                        return redirect()->back()->withCookies()->with("success", "berhasil diperbarui");
                    } catch (\Exception $e) {
                        return redirect()->back()->withCookies()->with("errors", $e->getMessage());
                    }
                }

                if($subpage === 'account_biodata') {
                    if(!$this->validate([
                        'fullname' => 'required',
                        'nuptk' => 'required',
                        'tmt-date' => 'required',
                        'tmt-sk' => 'required',
                        'ijazah-date' => 'required',
                        'ijazah-id' => 'required',
                    ])) return redirect()->back()->withCookies()->with("errors", "Invalid To update");
                    $valid = $this->validator->getValidated();
                    $db = db_connect();
                    $edu_bio = $db->table("edu_biodata");
                    $count = $edu_bio->where("edu_id", $id)->selectCount("id", "count")->get()->getFirstRow();
                    if($count === 1) {
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

            break;
            case 'events' :
                if(!in_array($subpage, $this->SUBPAGE)) return redirect()->back()->withCookies()->with("errors", "halaman berubah sewaktu mengirim data");
                switch($subpage) {
                    case 'create' :
                        $rules = [
                            'tema' => 'required',
                            'jenis' => 'required',
                            'sesi' => 'required',
                            'ketua' => 'required',
                        ];
                    break; 
                    case 'edit' :
                        $rules = [
                            'tema' => 'required',
                            'jenis' => 'required',
                            'sesi' => 'required',
                            'ketua' => 'required',
                        ];
                        $id = $this->request->getGet('id');
                        if(!$id) return redirect()->back()->withCookies()->with("errors", "halaman berubah sewaktu mengirim data");
                    break;
                    case 'delete' :
                        $rules = [
                            'id' => 'required',
                        ];
                    break;
                }
                

                if(!$this->validate($rules)) return redirect()->back()->withCookies()->with("errors", "Input data yang di masukkan tidak valid, pastikan semuanya di isi tanpa terkecuali");
                $valid = $this->validator->getValidated();
                $activity = new Activity();
                try {
                    //code...
                    if($subpage === 'delete') {
                        $activity->delete($valid['id']);
                    } elseif($subpage === 'edit') {
                        $activity->set("session_id", $valid['sesi']);
                        $activity->set("admin_id" , explode("_", $valid['ketua'])[0]);
                        $activity->set("admin_name" , explode("_", $valid['ketua'])[1]);
                        $activity->set("name" , $valid['tema']);
                        $activity->set( "jenis", $valid['jenis']);
                        $activity->set('status' , 'end');
                        $activity->update($id);
                        
                    } else {
                        $activity->save([
                            "session_id" => $valid['sesi'],
                            "admin_id" => explode("_", $valid['ketua'])[0],
                            "admin_name" => explode("_", $valid['ketua'])[1],
                            "name" => $valid['tema'],
                            "jenis" => $valid['jenis'],
                            "status" => "start"
                        ]);
                    }
                    return redirect()->back()->withCookies()->with("success", "Berhasil melakukan $subpage data");
                } catch (\Throwable $th) {
                    //throw $th;
                    return var_dump($th);
                    // return redirect()->back()->withCookies()->with("errors", "Terjadi masalah saat melakukan $subpage data");
                }

        }

        return redirect()->back()->withCookies()->with("errors", "Sebaiknya anda segera hubungi operator");

        
    }

    protected function res() {
        $contentType = $this->request->getServer('content_type');
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
