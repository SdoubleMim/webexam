<?php
namespace App\Controller;

use App\Model\User;
use App\Model\Post;
use Exception;
use Carbon\Carbon;

class AuthController 
{
    public function register() 
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate inputs
                $name = $this->sanitizeInput($_POST['name'] ?? '');
                $email = $this->sanitizeInput($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                $confirmPassword = $_POST['confirm_password'] ?? '';
                
                // Field validation
                if (empty($name) || empty($email) || empty($password)) {
                    throw new Exception('All fields are required');
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Invalid email format');
                }

                if (strlen($password) < 6) {
                    throw new Exception('Password must be at least 6 characters');
                }

                if ($password !== $confirmPassword) {
                    throw new Exception('Passwords do not match');
                }

                // Check for duplicate email
                if (User::where('email', $email)->exists()) {
                    throw new Exception('Email already registered');
                }

                // Create user - رمز عبور خام ارسال می‌شود (مدل User آن را هش می‌کند)
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_BCRYPT)
                ]);

                // Generate sample posts
                $postCount = rand(5, 7);
                for ($i = 1; $i <= $postCount; $i++) {
                    Post::create([
                        'title' => 'Post ' . $i . ' by ' . $name,
                        'content' => 'Sample content for post ' . $i,
                        'user_id' => $user->id,
                        'created_at' => Carbon::now()->subDays(rand(0, 30))
                    ]);
                }

                // Auto-login user
                $_SESSION['user'] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ];
                
                header('Location: /webexam/');
                exit;
            }
            
            $this->view('auth/register');
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /webexam/register');
            exit;
        }
    }

    public function login()
    {
        error_log("POST data: " . print_r($_POST, true));
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitizeInput($_POST['email']);
            $password = $_POST['password'];

            try {
                $user = User::where('email', $email)->first();

                if (!$user) {
                    throw new Exception('User not found');
                }

                if (!password_verify($password, $user->password)) {
                    error_log("Password mismatch for user: " . $email);
                    throw new Exception('Invalid credentials');
                }

                $_SESSION['user'] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ];

                header('Location: /webexam/posts');
                exit;
            } catch (Exception $e) {
                $this->view('auth/login', [
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            $this->view('auth/login');
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /webexam/login');
        exit;
    }

    private function sanitizeInput($data)
    {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    protected function view($path, $data = [])
    {
        extract($data);
        require __DIR__ . "/../../views/{$path}.php";
    }
}