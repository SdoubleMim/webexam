<?php
namespace App\Controller;

use App\Model\Post;

// At the top of PostController.php
use Illuminate\Support\Facades\DB;

use Exception; 

use App\Model\RelatedPost;
class PostController {
    public function index() {
        $posts = Post::with('user')->get();
        view('posts/index', compact('posts'));
    }

        // Add this if not using Laravel's abort helper
    protected function abort($code, $message = '') {
        http_response_code($code);
        die($message);
    }

    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            // نمایش صفحه 404 سفارشی
            return abort(404, 'Post not found');
        }

        return view('posts/show', ['post' => $post]);
    }

    public function create() {
        view('posts/create');
    }

    public function store() {
        Post::create([
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'user_id' => $_POST['user_id'] ?? 1 // Default to 1 if not provided
        ]);
        redirect('/posts');
    }

    public function edit($id)
    {
        try {
            $post = Post::find($id);
            
            if (!$post) {
                throw new Exception("Post not found");
            }
            
            view('posts/edit', ['post' => $post]);
            
        } catch (Exception $e) {
            error_log($e->getMessage());
            abort(404, 'Post not found');
        }
    }

    public function update($id) {
        $post = Post::findOrFail($id);
        $post->update([
            'title' => $_POST['title'],
            'content' => $_POST['content'],
        ]);
        redirect("/posts/{$id}");
    }

    public function delete($id) {
        $post = Post::findOrFail($id);
        $post->delete();
        redirect('/posts');
    }

    // Temporary connection test method
    public function testConnection() {
        try {
            DB::connection()->getPdo();
            echo "Connected successfully to: " . DB::connection()->getDatabaseName();
        } catch (Exception $e) {
            die("Could not connect to the database. Error: " . $e->getMessage());
        }
    }

    public function related() {
        try {
            $relations = RelatedPost::with(['post1', 'post2'])->get();
            
            if ($relations->isEmpty()) {
                throw new Exception("هیچ رابطه‌ای بین پست‌ها وجود ندارد");
            }
            
            require_once __DIR__ . '/../../views/posts/related.php';
        } catch (Exception $e) {
            http_response_code(500);
            echo "خطا: " . $e->getMessage();
        }
    }
}