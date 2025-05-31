<?php
// app/Controller/AuthController.php
namespace App\Controller;

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // پردازش لاگین
            $this->handleLogin();
            return;
        }
        
        // نمایش فرم لاگین
        require_once __DIR__ . '/../../views/auth/login.php';
    }

    private function handleLogin() {
        // پیاده‌سازی عملیات لاگین
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // پردازش ثبت‌نام
            $this->handleRegister();
            return;
        }
        
        // نمایش فرم ثبت‌نام
        require_once __DIR__ . '/../../views/auth/register.php';
    }

    private function handleRegister() {
        // پیاده‌سازی عملیات ثبت‌نام
    }

    public function logout() {
        // عملیات خروج کاربر
        session_destroy();
        header('Location: /login');
        exit;
    }
}