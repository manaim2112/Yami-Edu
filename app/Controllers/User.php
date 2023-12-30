<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Messages;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class User extends BaseController
{    
    public function index(String $username = null, String $path = null, String $subpath = null) : String|RedirectResponse
    {
    //    // Check if User not defined username in path
        if(!isset($username)) return redirect()->to(url_to("user", $this->request->user->user_username));
        if($username !== $this->request->user->user_username) return view("errors/404");

        $pathname = ['message', 'cbt', 'raport', 'settings', 'history', 'value'];

        if(isset($path) && in_array($path, $pathname)) {
            if($subpath == null) {
                $db = db_connect();

                switch($path) {
                    case 'message':
                        $data = new Messages();
                        $result = $data->where('to', $this->request->user->user_id)->findAll();
                    break;
                    case 'cbt' :
                        $builder = $db->table('cbt_room');
                        $builder->select('cbt_room.activity_id, activities.name');
                        $builder->join('activities', 'activities.id = cbt_room.activity_id', 'left');
                        $builder->where('cbt_room.users', '|' . $db->escapeLikeString($this->request->user->user_id) . '|', 'LIKE');

                        $result = $builder->get()->getResult();

                    break;
                }

                return view("users/page/" . $path, ['data' => $result ?? [], 'auth' => $this->request->user]);
            }

            return view("users/page/" . $path, ["subpath" => $subpath]);
        }

        return view("users/home",['auth' => $this->request->user]);
    }

    protected function messages_page(String $path2 = null, String $path3 = null) {

    }

    /**
     * Controller Authentication for User Management
     * @param String $any optional match for 'login' or 'registration'
     * @return String
     */
    public function auth(String $any = null) : String|RedirectResponse
    {
        if(!isset($any)) return redirect()->route("user.auth", ["any" => "login"]);

        if(!in_array($any, ['login', 'register', 'logout'])) {
            return "404 Not FOund";
        }
        $session = session();
        if($this->request->is("get")) {
            if($any === 'logout') {
                $session->remove("auth_user_isLoggedIn");
                $session->remove("auth_user_id");
                $session->remove("auth_user_username");
                $session->remove("auth_user_email");
                
                return redirect()->to(url_to("user.auth", 'login'));
            }
            if($session->get("auth_user_username")) return redirect()->to(url_to("user", $session->get("auth_user_username")));

            return view("users/auth", ["lOrR" => $any]);
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

        if(!$this->validate($rules)) return view("users/auth", ["errors" => $this->validator->getErrors(), "lOrR" => $any]);

        $validData = $this->validator->getValidated();
        $user = new UserModel();

        if($any === 'register') {
            $validData['password'] = password_hash($validData['password'], PASSWORD_DEFAULT);
            $user->insert($validData);
            return redirect()->to(route_to('users/auth', 'login'))->withCookies()->with("success", "Registration Success");
        }

        
        $pass = $user->orWhere("username", $validData["username"])->orWhere("email", $validData["username"])->first();
        if(!isset($pass)) return view("users/auth", ["errors" => $validData["username"] . " belum terdaftar"]);
        if(!password_verify($validData["password"], $pass['password'])) return view("users/auth", ["errors" => $validData["username"] . " Sandi salah", "lOrR" => $any]);
        
        $session->set("auth_user_isLoggedIn", true);
        $session->set("auth_user_id", $pass['id']);
        $session->set("auth_user_username",$pass['username']);
        $session->set("auth_user_email",$pass['email']);
        return redirect()->to(url_to("user", $pass['username']));
    }
}
