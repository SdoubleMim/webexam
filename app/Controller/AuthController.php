<?php
namespace App\Controller;

use App\Model\User;
use Exception;

class AuthController 
{
    public function login() 
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $this->sanitizeInput($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                
                if (empty($email) || empty($password)) {
                    throw new Exception('Email and password are required');
                }
                
                $user = User::where('email', $email)->first();
                
                if (!$user || !$user->verifyPassword($password)) {
                    throw new Exception('Invalid credentials');
                }
                
                $_SESSION['user'] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ];
                
                header('Location: /webexam/');
                exit;
            }
            
            require_once __DIR__ . '/../../views/auth/login.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /webexam/login');
            exit;
        }
    }

    public function register() 
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $this->sanitizeInput($_POST['name'] ?? '');
                $email = $this->sanitizeInput($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                $confirmPassword = $_POST['confirm_password'] ?? '';
                
                if (empty($name) || empty($email) || empty($password)) {
                    throw new Exception('All fields are required');
                }
                
                if (strlen($password) < 6) {
                    throw new Exception('Password must be at least 6 characters');
                }
                
                if ($password !== $confirmPassword) {
                    throw new Exception('Passwords do not match');
                }
                
                if (User::where('email', $email)->exists()) {
                    throw new Exception('Email already registered');
                }
                
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => $password
                ]);
                
                $_SESSION['user'] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ];
                
                header('Location: /webexam/');
                exit;
            }
            
            require_once __DIR__ . '/../../views/auth/register.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /webexam/register');
            exit;
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
}