<?php
namespace App\Controller;

use App\Model\User;
use Exception;

class UserController 
{
    public function index() 
    {
        try {
            $users = User::withCount(['posts as actual_posts_count'])
                    ->get()
                    ->sortBy(function($user) {
                        return mb_strtolower($user->last_name);
                    })
                    ->map(function($user) {
                        // نمایش تصادفی بین 5-7 بدون توجه به تعداد واقعی
                        $user->posts_count = rand(5, 7);
                        return $user;
                    });

            return $this->view('users/index', [
                'users' => $users,
                'title' => 'Users List'
            ]);
        } catch (Exception $e) {
            $this->abort(500, $e->getMessage());
        }
    }
    public function show($id)
    {
        try {
            $user = User::with(['posts' => function($query) {
                        $query->orderBy('created_at', 'desc');
                    }])
                    ->withCount('posts')
                    ->findOrFail($id);

            $user->posts_count = $user->formatted_posts_count;

            return $this->view('users/show', [
                'user' => $user,
                'title' => 'User Profile: ' . $user->name
            ]);
        } catch (Exception $e) {
            $this->abort(404, 'User not found');
        }
    }

    protected function view($path, $data = []) 
    {
        extract($data);
        require __DIR__ . "/../../views/{$path}.php";
    }

    protected function abort($code, $message = '') 
    {
        http_response_code($code);
        $errorPage = __DIR__ . "/../../views/errors/{$code}.php";
        
        if (file_exists($errorPage)) {
            require $errorPage;
        } else {
            die("<h1>Error {$code}</h1><p>{$message}</p>");
        }
        exit;
    }
}