<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class User implements FilterInterface
{

    public $globals = [
        'before' => [
            // 'honeypot',
            'csrf' =>  [
                'except' => [
                    'api/record/[0-9]+',
                    'u/account/(:any)',
                    'u/auth/login',
                    'u/auth/register',
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
        $auth = $session->get("auth_user_isLoggedIn");
        if(!$auth) return redirect()->to(url_to('user.auth', 'login'));

        if(isset($json)) return redirect()->to(url_to('user.auth', 'login'));

        $user= new \StdClass();

        $user->user_fullname = $session->get('auth_user_fullname');
        $user->user_username = $session->get('auth_user_username');
        $user->user_email = $session->get('auth_user_email');
        $user->user_id = $session->get('auth_user_id');

        $request->user = $user;
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
