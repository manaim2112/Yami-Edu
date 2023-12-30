<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use stdClass;

class Admin implements FilterInterface
{

    public $globals = [
        'before' => [
            // 'honeypot',
            'csrf' =>  [
                'except' => [
                    'api/record/[0-9]+',
                    'a/account',
                    'a/account/(:any)',
                    'a/auth/login',
                    'a/auth/register',
                ]
            ],
        ],
    ];

    public $methods = [
        'get'  => ['csrf'],
        'post' => ['csrf'],
    ];


    
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */

    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $auth = $session->get("admin_isLoggedIn");
        if(!$auth) return redirect()->to(url_to('admin.auth', 'login'));

        if(isset($json)) return redirect()->to(url_to('admin.auth', 'login'));
        $admin = new \stdClass();
        $admin->name = $session->get('admin_username');
        $admin->full = $session->get('admin_fullname');
        $admin->role = $session->get('admin_role');
        $admin->id = $session->get('admin_id');
        $admin->email = $session->get('admin_email');
        $admin->access = in_array($admin->role, [1,2,3]) ? true : false;
        $admin->access = in_array($admin->role, [5]) ? null : $admin->access;
        $admin->access = in_array($admin->role, [4]) ? false : $admin->access; 
        $request->admin = $admin;
        return $request;
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
       
    }
}
