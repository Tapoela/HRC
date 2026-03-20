<?php 

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // ⭐ Routes that DO NOT require login
        $openRoutes = [
            'login',
            'logout',
            'player/setup',     // onboarding link
            'player/setup/'     // safety for trailing slash
        ];

        $uri = service('uri')->getPath();

        // Allow open routes
        foreach ($openRoutes as $route) {
            if (strpos($uri, $route) === 0) {
                return; // skip auth check
            }
        }

        // Normal auth check
        if (!session()->get('logged_in') || !session()->get('user_id')) {
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}