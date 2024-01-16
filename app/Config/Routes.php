<?php

use CodeIgniter\Router\RouteCollection;

// $routes->setAutoRoute(true); 

/**
 * @var RouteCollection $routes
 */
// $routes->group('', ['filter' => \App\Filters\PublicFilter::class], static function($routes) {

//     $routes->get("/", 'Home::index');

// });

// $routes->get("install/(:segment)", "Install:index/$1", ['as' => 'install.index']);


$routes->get('/', 'Home::index', ['as' => 'home']);
$routes->get("/p/(:page)", "Home::page/$1", ['as' => 'page']);
$routes->get("/blog/(:segment)?", "Home::blog/$1", ['as' => 'blog']);

$routes->get("/setup", "Setup::index", ['as' => 'install']);
$routes->match(['get', 'post'], "/setup/(:segment)?", "Setup::index/$1", ['as' => 'install.index']);


/**
 * @Route User
 * 
 */
$routes->get("/u/account", "User::index", ['filter' => \App\Filters\User::class]);
$routes->get("/u/account/(:any)?", "User::index/$1/$2/$3", ['as' => 'user', 'filter' => \App\Filters\User::class]);
$routes->match(['get', 'post'], '/u/auth/(:segment)?', "User::auth/$1", ['as' => 'user.auth']);


/**
 * @Route Admin
 */

 $routes->group("a/account", static function ($routes) {
    $routes->get("", function() {
        return redirect()->to(route_to('edu.index', [auth("edu")->id]));
    });

    $routes->group("(:segment)", ['as' => 'edu'],  static function($routes) {
        $filters = ['filter' => \App\Filters\Admin::class];

        $routes->get("", "EduController::index", ['as' => 'edu.index'] + $filters);
        $routes->get("educational_staf", "EduController::educational_staf", ['as' => 'edu.educational_staf'] + $filters);
        $routes->post("educational_staf/create", "EduPostController::educational_staf/create", ['as' => 'edu.educational_staf.create.post'] + $filters);
        $routes->get("learners_user", "EduController::learners_user", ['as' => 'edu.learners_user'] + $filters);
        $routes->get("learners_user/manage_kelas", "EduController::learners_user/manage_kelas", ['as' => 'edu.learners_user.kelas'] + $filters);
        $routes->post("learners_user/manage_kelas/create", "EduPostController::learners_user/manage_kelas/create", ['as' => 'edu.learners_user.kelas.create'] + $filters);
        $routes->post("learners_user/manage_kelas/edit", "EduPostController::learners_user/manage_kelas/edit", ['as' => 'edu.learners_user.kelas.edit'] + $filters);
        $routes->post("learners_user/manage_kelas/delete", "EduPostController::learners_user/manage_kelas/delete", ['as' => 'edu.learners_user.kelas.delete'] + $filters);
        $routes->get("learners_user/template_user", "EduController::learners_user/template_user", ['as' => 'edu.learners_user.template'] + $filters);
        $routes->get("learners_user/upload", "EduPostController::learners_user/upload", ['as' => 'edu.learners_user.upload.post'] + $filters);
        $routes->get("events", "EduController::events", ['as' => 'edu.events'] + $filters);
        $routes->post("events/create", "EduPostController::events/create", ['as' => 'edu.events.create.post'] + $filters);
        $routes->post("events/edit", "EduPostController::events/edit", ['as' => 'edu.events.edit.post'] + $filters);
        $routes->get("templates/(:segment)", "EduController::templates", ['as' => 'edu.templates'] + $filters);
        $routes->get("setting_account", "EduController::setting_account", ['as' => 'edu.setting_account'] + $filters);
        $routes->post("setting_account", "EduPostController::setting_account", ['as' => 'edu.setting_account.post'] + $filters);
        $routes->get("setting_site", "EduController::setting_site", ['as' => 'edu.setting_site'] + $filters);
        $routes->post("setting_site", "EduPostController::setting_site", ['as' => 'edu.setting_site.post'] + $filters);
    });
});



// $routes->get("/a/account", "Admin::index", ['filter' => \App\Filters\Admin::class]);
// $routes->get("/a/account/(:any)?", "Admin::index/$1/$2/$3", ['as' => 'admin', 'filter' => \App\Filters\Admin::class]);
// $routes->post("/a/account/(:any)?", "Admin::post/$1/$2/$3", ['as' => 'admin.post', 'filter' => \App\Filters\Admin::class]);
$routes->match(['get', 'post'], '/a/auth/(:segment)?', "EduController::auth/$1", ['as' => 'admin.auth']);




// $routes->get("/a/automatically/session", function() {
//     helper('date');
//     try {
//         //code...
//         $session = new Session();
//         $idSession = $session->insert([
//             "name" => date("Y") . '/' . date("Y")+1,
//             "current" => true,
//         ], true);
//         $db = db_connect();
//         $db->table("session")->([
//             'name' => ,
//             'current' => true,
//             'created_at' => date('Y-m-d H:i:s', now())
//         ]);

//         if($db->table("session_sub")->countAll() <= 2) {
//             $db->table("session_sub")->insert([
//                 [
//                     "name" => "Semester 1",
//                     "current" => true,
//                 ],
//                 [
//                     "name" => "Semester 2",
//                     "current" => true,
//                 ]
//             ]);
//         };
//         return redirect()->back();
//     } catch (\Throwable $th) {
//         return redirect()->back();
//         //throw $th;
//     }
// }, ['as' => 'auto.session', 'filter' => \App\Filters\Admin::class]);
// $routes->set404Override(function() {
//     cache()->save("view", view("errors/404"));
//     return view("errors/404", [], ['cache' => false]);
// });

if(isset(setting()->themes)) {
    $path = ROOTPATH . "\/plugin/" . setting()->themes . '\/routes.php';
    if(file_exists($path)) {
        require_once $path;
    }
}



