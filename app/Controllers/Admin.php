<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Activity;
use App\Models\Admin as ModelsAdmin;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;
use Psr\Log\NullLogger;

class Admin extends BaseController
{
    protected $pathname = ['users', 'teams', 'cbt', 'raport', 'settings', 'history', 'value', 'events'];
    protected $SUBPAGE = ['create', 'edit', 'delete'];

    public function index(String $username = null, String $page = null, String $subpage = null) : String|RedirectResponse|null
    {
        if($this->request->is("post")) return $this->post($username, $page, $subpage);

        if(!$username) return redirect()->to(url_to("admin", $this->getAuth('name')));
        if(!$page) return view("admin/home");
       
        if(!in_array($page, $this->pathname)) return view('errors/404');

        $db = db_connect();

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
                    $SUBPAGE = ['create', 'edit', 'delete'];
                    if(!in_array($subpage, $SUBPAGE)) return view("errors/404");
                    if($subpage === 'edit') {
                        $data = new Activity();
                        $data = $data->find($this->request->getGet('id'));
                    }
                } else {
                    $data = new Activity();
                    $data = $data->findAll();
                }
            break;
            case 'users' :
                if($subpage) {

                } else {
                    $data = $db->table("users");
                    $data->select('users.id, userdetails.fullname, users.username, userdetails.category');
                    $data->join('userDetails', 'users.id = userdetails.user_id', 'left');
                    $data = $data->get()->getResultObject();
                }
        }
        $this->cachePage(60);

        return view("admin/page/" . $page, ['data' => $data ?? [], 'subpage' => $subpage], ['cache' => false]);
    }

    public function post(String $username = null, String $page = null, String $subpage = null) : String|RedirectResponse|null
    {
        if(!$username || $username !== $this->getAuth('name')) return redirect()->back()->withCookies()->with("errors", "Tindakan ilegal tidak diperbolehkan");
        if(!in_array($page, $this->pathname)) return redirect()->back()->withCookies()->with("errors", "Permintaan data anda tidak ditemukan");

        switch($page) {
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
        $user = new ModelsAdmin();
        
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

        return redirect()->to(url_to("admin", $pass['username']));
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
