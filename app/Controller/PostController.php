<?php
namespace App\Controller;

use App\Model\Post;
use App\Model\RelatedPost;
use Exception;

class PostController 
{
    const ERROR_NOT_AUTHORIZED = 'You are not authorized to perform this action';
    const ERROR_POST_NOT_FOUND = 'Post not found';

// app/Controller/PostController.php
    public function index()
    {
        try {
            $posts = Post::with(['user'])
                    ->orderBy('created_at', 'desc')
                    ->get();

            $this->view('posts/index', [
                'posts' => $posts,
                'currentUser' => $_SESSION['user_id'] ?? null
            ]);
        } catch (Exception $e) {
            $this->abort(500, 'Error loading posts');
        }
    }

    public function show($id)
    {
        try {
            $post = Post::with(['user', 'views'])
                ->findOrFail($id);

            if (isset($_SESSION['user_id'])) {
                $post->views()->firstOrCreate([
                    'user_id' => $_SESSION['user_id']
                ]);
            }

            $relatedPosts = RelatedPost::where('post_id', $id)
                ->orWhere('related_post_id', $id)
                ->with(['post', 'relatedPost'])
                ->limit(5)
                ->get();

            $this->view('posts/show', [
                'post' => $post,
                'relatedPosts' => $relatedPosts,
                'canEdit' => $this->canEditPost($post)
            ]);
            
        } catch (Exception $e) {
            $this->abort(404, self::ERROR_POST_NOT_FOUND);
        }
    }

    public function related($id)
    {
        try {
            $post = Post::with(['user'])->findOrFail($id);
            
            $relatedPosts = RelatedPost::where('post_id', $id)
                            ->orWhere('related_post_id', $id)
                            ->with(['post.user', 'relatedPost.user'])
                            ->get()
                            ->map(function ($relation) use ($id) {
                                return $relation->post_id == $id ? $relation->relatedPost : $relation->post;
                            })
                            ->filter();

            $this->view('posts/related', [
                'post' => $post,
                'relatedPosts' => $relatedPosts,
                'title' => 'Related Posts: ' . $post->title,
                'currentUser' => $_SESSION['user_id'] ?? null
            ]);
        } catch (Exception $e) {
            $this->abort(404, 'Post or related posts not found');
        }
    }

    public function users($id)
    {
        try {
            $post = Post::with(['user'])->findOrFail($id);
            
            $this->view('posts/users', [
                'post' => $post,
                'user' => $post->user
            ]);

        } catch (Exception $e) {
            $this->abort(404, self::ERROR_POST_NOT_FOUND);
        }
    }

    private function canEditPost($post)
    {
        return isset($_SESSION['user_id']) && 
               $post->user_id == $_SESSION['user_id'];
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