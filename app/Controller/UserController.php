<?php
namespace App\Controller;

use App\Model\User;
use App\Model\Post;
use Exception;

class UserController 
{
    public function index() 
    {
        try {
            // Get users alphabetically with post counts
            $users = User::withCount('posts')
                ->orderBy('name')
                ->get();
                
            $this->view('users/index', [
                'users' => $users,
                'title' => 'Students List'
            ]);
            
        } catch (Exception $e) {
            $this->abort(500, 'Error loading user list');
        }
    }

    public function show($id) 
    {
        try {
            // Get user with their posts (newest first)
            $user = User::with(['posts' => function($query) {
                $query->orderBy('created_at', 'DESC');
            }])->findOrFail($id);
            
            $this->view('users/show', [
                'user' => $user,
                'posts' => $user->posts,
                'postCount' => $user->posts->count()
            ]);
            
        } catch (Exception $e) {
            $this->abort(404, 'User not found');
        }
    }

    public function delete($id) 
    {
        $this->checkAuth();
        
        try {
            $user = User::findOrFail($id);
            
            // Prevent deletion if user has posts
            if ($user->posts()->exists()) {
                throw new Exception('Cannot delete users with existing posts');
            }
            
            $user->delete();
            $_SESSION['success'] = 'User deleted successfully';
            $this->redirect('/users');
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('/users');
        }
    }

    // ============ Helper Methods ============
    
    protected function view($path, $data = []) 
    {
        extract($data);
        require __DIR__ . "/../../views/{$path}.php";
    }
    
    protected function redirect($url) 
    {
        header("Location: {$url}");
        exit;
    }
    
    protected function abort($code, $message = '') 
    {
        http_response_code($code);
        $errorPage = __DIR__ . "/../../views/{$code}.php";
        
        if (file_exists($errorPage)) {
            require $errorPage;
        } else {
            die($message);
        }
        exit;
    }
    
    private function checkAuth() 
    {
        if (!isset($_SESSION['user_id'])) {
            $this->abort(401, 'Please login first');
        }
    }
}