<?php

namespace App\Controllers;

use App\Models\Blog;
use App\Models\Page;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Home extends BaseController
{
    public function index(): string | RedirectResponse
    {
        $set = new \App\Models\Setting();
        $checking = $set->where("name", "installed", true)->get()->getFirstRow();
        if(!$checking) return redirect()->to(url_to("install"));
        if($checking->v !== 'yes') return redirect()->to(url_to("install"));

        $db = db_connect();
        $d = $db->table("setting")->where("name", "brand_name")
            ->orWhere("name", "brand_logo")
            ->orWhere("name", "navigation")
            ->get()
            ->getResultObject();
        $newNav = array();
        foreach ($d as $value) {
            $newNav[$value->name] = $value->v;
        }
        return view('welcome', [
            "title" => lang("Validation.title.home") . " SSR",
            "navigation" => $newNav
        ]);
    }

    public function page(String $slug) : string
    {
        $page = new Page();
        if(isset($slug)) {
            $current = $page->where("slug", htmlspecialchars($slug))->first();
        } else {
            $current = $page->findAll(10, 0);
        }
           
        return view("pages", ["data" => $current]);
    }


    public function blog(String $slug = null)  : string|RedirectResponse {

        $blogs = new Blog();

        if(!isset($slug)) {
            $current = $blogs->findAll(10, 0);
        } else {
            $current = $blogs->where("slug", $slug)->first();
        }
        
        return view("blogs", array("current" => $current));
    }

    public function get() {
        helper(['form']);
        $rules = [
            'name'          => 'required|min_length[2]|max_length[50]',
            'email'         => 'required|min_length[4]|max_length[100]|valid_email|is_unique[users.email]',
            'password'      => 'required|min_length[4]|max_length[50]',
            'confirmpassword'  => 'matches[password]'
        ];
          
        if($this->validate($rules)){
            $userModel = new UserModel();
            $data = [
                'name'     => $this->request->getVar('name'),
                'email'    => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ];
            $userModel->save($data);
            return redirect()->to('/signin');
        }else{
            $data['validation'] = $this->validator;
            echo view('signup', $data);
        }
    }
}
