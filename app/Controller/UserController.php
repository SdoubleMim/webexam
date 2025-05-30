<?php
namespace App\Controller;

use App\Model\User;

class UserController {
    public function index() {
        $users = User::all();
        require __DIR__.'/../../views/users/index.php';
    }

    // public function show($id) {
    //     $user = User::find($id);
    //     require __DIR__.'/../../views/users/show.php';
    // }

    public function show($id) {
    $user = User::find($id);
    view('users/show', ['user' => $user]); // ارسال داده به ویو
    }

    public function delete($id) {
        // پردازش حذف کاربر...
        redirect('/users'); // ریدایرکت پس از عملیات
    }
}
?>