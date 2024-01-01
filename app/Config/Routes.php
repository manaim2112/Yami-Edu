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

$routes->get("/configuration", "Install::index", ['as' => 'install']);

$routes->match(['get', 'post'], "/configuration/(:segment)?", "Install::index/$1", ['as' => 'install.index']);


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
$routes->get("/a/account", "Admin::index", ['filter' => \App\Filters\Admin::class]);
$routes->get("/a/account/(:any)?", "Admin::index/$1/$2/$3", ['as' => 'admin', 'filter' => \App\Filters\Admin::class]);
$routes->post("/a/account/(:any)?", "Admin::post/$1/$2/$3", ['as' => 'admin.post', 'filter' => \App\Filters\Admin::class]);
$routes->match(['get', 'post'], '/a/auth/(:segment)?', "Admin::auth/$1", ['as' => 'admin.auth']);




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
$routes->set404Override(function() {
    cache()->save("view", view("errors/404"));
    return view("errors/404", [], ['cache' => false]);
});



