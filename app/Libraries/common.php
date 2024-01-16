<?php
if(! function_exists('setting')) {
    function setting($reset = false) : object|bool {
        try {
            $db = db_connect();
            
            $newSetting = cache("setting") ?: new \stdClass();
            
            if(!isset($newSetting->installed)) {
                $settings = $db->table("setting")->ignore(true)->select('name, v')->get()->getResultObject();
                foreach($settings as $setting) {
                    $newSetting->{$setting->name} = $setting->v;
                }
                cache()->save("setting", $newSetting);
            }

            if($reset) {
                cache()->clean();
                $settings = $db->table("setting")->ignore(true)->select('name, v')->get()->getResultObject();
                foreach($settings as $setting) {
                    $newSetting->{$setting->name} = $setting->v;
                }
                cache()->save("setting", $newSetting);
            }

            return $newSetting;
        } catch (\Exception $e) {
            return false;
        }

    }
}


if(! function_exists('auth')) {
    /**
     * Simpan Semua data session di Authorization-secure-with-{edu|user}
     * data yang di simpan di encrypt
     * @param string $type user|edu
     * @object isLoggedIn - sebagai check sudah masuk atau belum
     * @object isType - sebagai check user atau edu
     * @object role sebagai leveling edu
     * @object username sebagai nama unik dari edu/user
     * @string fullname sebagai nama lengkap dari edu/user
     * @object email sebagai email aktif dari edu/user
     * @return Object has|set
     */

    class Auth {
        protected $session;
        protected $type;
        protected $string;
        public $id = null;
        public $isLoggedIn = false;
        public $isType = 'user';
        public $role = [];
        public $username;
        public $fullname;
        public $email;

        public function __construct($type = 'user')
        {
            $this->type = $type;
            $this->session = session();
            $this->string = "Authorization-secure-with-" . $type;
            if($this->has()) {
                $this->id = $this->get('id');
                $this->isLoggedIn = $this->get("isLoggedIn");
                $this->isType = $this->get("isType");
                $this->role = $this->get("role");
                $this->username = $this->get("username");
                $this->fullname = $this->get("fullname");
                $this->email = $this->get("email");
            }
        }

        /**
         * @return mixed Session Authentication
         */
        public function get($key) {
            $encrypt = $this->session->get($this->string);
            if($this->has()) {
                return $encrypt->$key;
            }
            return null;
        }

        public function set($data = null) {
            if($data === null) {
                $data = new \stdClass();
                $data->id = $this->id;
                $data->isLoggedIn = $this->isLoggedIn;
                $data->isType = $this->isType;
                $data->role = $this->role;
                $data->username = $this->username;
                $data->fullname = $this->fullname;
                $data->email = $this->email;
            }
            $this->session->set("Authorization-secure-with-" . $this->type, $data);
        }

        public function has() {
            return $this->session->has($this->string);
        }

        public function reset() {
            $this->session->remove($this->string);
        }

        public function is_role($role) {
            $status = false;
            foreach($role as $r) {
                if(in_array($r, $this->role)) {
                    $status = true;
                    break;
                }
            }
            return $status;
        }
    }
    function auth($type = 'user') : Auth {
        return new Auth($type);
    }
}