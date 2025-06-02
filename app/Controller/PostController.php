<?php
namespace App\Controller;

use App\Model\Post;
use App\Model\RelatedPost;
use App\Model\User;
use Exception;

class PostController 
{
    const ERROR_NOT_AUTHORIZED = 'You are not authorized to perform this action';
    const ERROR_POST_NOT_FOUND = 'Post not found';

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

            $relatedPosts = $this->getRelatedPosts($id);

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
            $relatedPosts = $this->getRelatedPosts($id);

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

    // public function allRelations()
    // {
    //     try {
    //         // ابتدا پست‌های حذف شده را جداگانه لود کنیم
    //         $relations = RelatedPost::with(['post.user', 'relatedPost.user'])
    //                     ->orderBy('created_at', 'desc')
    //                     ->get();

    //         // فیلتر کردن روابط معتبر
    //         $validRelations = $relations->filter(function($relation) {
    //             return $relation->post !== null && $relation->relatedPost !== null;
    //         });

    //         $this->view('posts/all_relations', [
    //             'relations' => $validRelations,
    //             'title' => 'All Post Relations'
    //         ]);
    //     } catch (Exception $e) {
    //         $this->abort(500, 'Error loading relations: ' . $e->getMessage());
    //     }
    // }

    public function allRelations()
    {
        try {
            $relations = RelatedPost::with(['post1.user', 'post2.user'])
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->filter(function($relation) {
                            return $relation->post1 !== null && $relation->post2 !== null;
                        });
            
            $this->view('posts/all_relations', [
                'relations' => $relations,
                'title' => 'Posts Relations'
            ]);
        } catch (Exception $e) {
            $this->abort(500, 'Error loading Relations ' . $e->getMessage());
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

    public function create()
    {
        $this->view('posts/create', [
            'currentUser' => $_SESSION['user_id'] ?? null
        ]);
    }

    public function store()
    {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception(self::ERROR_NOT_AUTHORIZED);
            }

            $post = new Post();
            $post->title = $_POST['title'] ?? '';
            $post->content = $_POST['content'] ?? '';
            $post->user_id = $_SESSION['user_id'];
            $post->save();

            header('Location: /webexam/posts/' . $post->id);
            exit;
        } catch (Exception $e) {
            $this->abort(403, $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $post = Post::findOrFail($id);

            if (!$this->canEditPost($post)) {
                throw new Exception(self::ERROR_NOT_AUTHORIZED);
            }

            $this->view('posts/edit', [
                'post' => $post,
                'currentUser' => $_SESSION['user_id'] ?? null
            ]);
        } catch (Exception $e) {
            $this->abort(403, $e->getMessage());
        }
    }

    public function update($id)
    {
        try {
            $post = Post::findOrFail($id);

            if (!$this->canEditPost($post)) {
                throw new Exception(self::ERROR_NOT_AUTHORIZED);
            }

            $post->title = $_POST['title'] ?? $post->title;
            $post->content = $_POST['content'] ?? $post->content;
            $post->save();

            header('Location: /webexam/posts/' . $post->id);
            exit;
        } catch (Exception $e) {
            $this->abort(403, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $post = Post::findOrFail($id);

            if (!$this->canEditPost($post)) {
                throw new Exception(self::ERROR_NOT_AUTHORIZED);
            }

            $post->delete();
            header('Location: /webexam/posts');
            exit;
        } catch (Exception $e) {
            $this->abort(403, $e->getMessage());
        }
    }

    private function getRelatedPosts($postId)
    {
        $relations = RelatedPost::where('post_id', $postId)
                        ->orWhere('related_post_id', $postId)
                        ->with(['post', 'relatedPost'])
                        ->get();

        return $relations->map(function ($relation) use ($postId) {
            return $relation->post_id == $postId ? $relation->relatedPost : $relation->post;
        })->unique('id');
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