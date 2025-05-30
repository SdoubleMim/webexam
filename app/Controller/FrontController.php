<?php
// namespace App\Controller;

// class FrontController {
//     public function home(): void {
//         require __DIR__.'/../../views/home.php';
//     }
// }

// <?php
namespace App\Controller;

class FrontController {
    public function home() {
        // مسیرهای صحیح برای هدر و فوتر
        require __DIR__.'/../../views/partials/header.php';
        require __DIR__.'/../../views/home.php';
        require __DIR__.'/../../views/partials/footer.php';
    }
}
?>