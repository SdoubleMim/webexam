<?php
// create_hash_passwords.php
require __DIR__ . '/vendor/autoload.php';
$config = require __DIR__ . '/config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Model\User;

// Initialize database
$capsule = new Capsule;
$capsule->addConnection($config);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Get all users
$users = User::all();

if (count($users) === 0) {
    die("No users found in database!\n");
}

echo "Starting password hashing process...\n\n";

foreach ($users as $user) {
    // Skip already hashed passwords
    if (password_needs_rehash($user->password, PASSWORD_BCRYPT)) {
        echo "Updating user: {$user->email}\n";
        echo "Old password: {$user->password}\n";
        
        // Trigger the setPasswordAttribute mutator
        $user->password = $user->password;
        $user->save();
        
        echo "New hashed password: {$user->password}\n";
        echo "------------------------\n";
    } else {
        echo "Skipping user (already hashed): {$user->email}\n";
    }
}

echo "\nPassword hashing completed!\n";
echo "Total users processed: " . count($users) . "\n";