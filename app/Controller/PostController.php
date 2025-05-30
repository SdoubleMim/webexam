<?php
namespace App\Controller;

use App\Model\Post;

class PostController {
    public function index() {
        $posts = Post::with('user')->get();
        view('posts/index', compact('posts'));
    }

    public function show($id) {
        $post = Post::with('user')->findOrFail($id);
        view('posts/show', compact('post'));
    }

    public function create() {
        view('posts/create');
    }

    public function store() {
        Post::create([
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'user_id' => 1 // فعلاً دستی یا از session
        ]);
        redirect('/posts');
    }

// در PostController.php
    public function edit($id) {
        $post = Post::find($id);
        view('posts/edit', ['post' => $post]); // ارسال داده به ویو
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
}
echo '<pre>';
var_dump($post);
echo '</pre>';
die();
?>