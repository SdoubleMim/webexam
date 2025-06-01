<?php
namespace App\Controller;
use Illuminate\Pagination\Paginator;
use App\Model\Post;
use App\Model\RelatedPost;
use App\Model\User;
use Exception;
use Carbon\Carbon;
class PostController 
{
    // Constants for error messages
    const ERROR_NOT_AUTHORIZED = 'You are not authorized to perform this action';
    const ERROR_POST_NOT_FOUND = 'Post not found';
    const ERROR_VALIDATION = 'Title and content are required';
    
    /**
     * Display a paginated list of posts
     */
    public function index() 
    {
        try {
            $posts = Post::with(['user' => function($query) {
                    $query->withDefault(['name' => '[Deleted User]']);
                }])
                ->orderBy('created_at', 'DESC')
                ->simplePaginate(10); // تغییر به simplePaginate
                
            return $this->view('posts/index', [
                'posts' => $posts,
                'currentUser' => $_SESSION['user_id'] ?? null
            ]);
            
        } catch (Exception $e) {
            $this->abort(500, 'Error loading posts: ' . $e->getMessage());
        }
    }

    /**
     * Display a single post with related posts
     */
    public function show($id)
    {
        try {
            $post = Post::with(['user', 'comments.user', 'views'])
                ->findOrFail($id);

            // Record view
            if (isset($_SESSION['user_id'])) {
                $post->views()->firstOrCreate([
                    'user_id' => $_SESSION['user_id']
                ]);
            }

            $relatedPosts = RelatedPost::where('post1_id', $id)
                ->orWhere('post2_id', $id)
                ->with(['post1.user', 'post2.user'])
                ->limit(5)
                ->get();

            return $this->view('posts/show', [
                'post' => $post,
                'relatedPosts' => $relatedPosts,
                'canEdit' => $this->canEditPost($post)
            ]);
        } catch (Exception $e) {
            $this->abort(404, self::ERROR_POST_NOT_FOUND);
        }
    }
        /**
     * Show post creation form
     */
    public function create() 
    {
        $this->requireAuth();
        return $this->view('posts/create');
    }

    /**
     * Store a new post
     */
    public function store() 
    {
        $this->requireAuth();
        
        try {
            $data = $this->validatePostRequest($_POST);
            
            $post = Post::create([
                'title' => $data['title'],
                'content' => $data['content'],
                'user_id' => $_SESSION['user_id'],
                'status' => 'published'
            ]);

            $this->setFlash('success', 'Post created successfully!');
            return $this->redirect("/posts/{$post->id}");

        } catch (Exception $e) {
            $this->setFlash('error', $e->getMessage());
            return $this->redirect('/posts/create');
        }
    }

    /**
     * Show post edit form
     */
    public function edit($id)
    {
        $this->requireAuth();
        
        try {
            $post = Post::findOrFail($id);
            $this->verifyOwnership($post);
            
            return $this->view('posts/edit', [
                'post' => $post
            ]);

        } catch (Exception $e) {
            $this->setFlash('error', $e->getMessage());
            return $this->redirect('/posts');
        }
    }

    /**
     * Update an existing post
     */
    public function update($id) 
    {
        $this->requireAuth();
        
        try {
            $post = Post::findOrFail($id);
            $this->verifyOwnership($post);
            
            $data = $this->validatePostRequest($_POST);
            
            $post->update([
                'title' => $data['title'],
                'content' => $data['content'],
                'updated_at' => Carbon::now()
            ]);

            $this->setFlash('success', 'Post updated successfully!');
            return $this->redirect("/posts/{$post->id}");

        } catch (Exception $e) {
            $this->setFlash('error', $e->getMessage());
            return $this->redirect("/posts/{$id}/edit");
        }
    }

    /**
     * Delete a post
     */
    public function delete($id) 
    {
        $this->requireAuth();
        
        try {
            $post = Post::findOrFail($id);
            $this->verifyOwnership($post);
            
            $post->delete();

            $this->setFlash('success', 'Post deleted successfully!');
            return $this->redirect('/posts');

        } catch (Exception $e) {
            $this->setFlash('error', $e->getMessage());
            return $this->redirect('/posts');
        }
    }

    /**
     * Display related posts
     */
    private function validatePostRequest($data)
    {
        if (empty($data['title'])) {  // پرانتز بسته اضافه شد
            throw new Exception('Post title is required');
        }
        
        if (empty($data['content'])) {
            throw new Exception('Post content is required');
        }
        
        if (strlen($data['title']) > 255) {
            throw new Exception('Title must be less than 255 characters');
        }
        
        return [
            'title' => $this->sanitize($data['title']),
            'content' => $this->sanitize($data['content'])
        ];
    }
    /**
     * Check if current user can edit the post
     */
    private function canEditPost($post)
    {
        return isset($_SESSION['user_id']) && 
               ($post->user_id == $_SESSION['user_id'] || $this->isAdmin());
    }

    /**
     * Check if user is admin
     */
    private function isAdmin()
    {
        // Implement your admin check logic
        return false; 
    }

    /**
     * Verify post ownership
     */
    private function verifyOwnership($post)
    {
        if (!$this->canEditPost($post)) {
            throw new Exception(self::ERROR_NOT_AUTHORIZED);
        }
    }

    /**
     * Require authenticated user
     */
    private function requireAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->setFlash('error', 'Please login to continue');
            $this->redirect('/login');
            exit;
        }
    }

    /**
     * Sanitize input data
     */
    private function sanitize($input)
    {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    /**
     * Set flash message
     */
    private function setFlash($type, $message)
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Render view
     */
    private function view($path, $data = [])
    {
        extract($data);
        require __DIR__ . "/../../views/{$path}.php";
    }

    /**
     * Redirect to URL
     */
    private function redirect($url)
    {
        header("Location: /webexam{$url}");
        exit;
    }

    /**
     * Abort with error
     */
    private function abort($code, $message)
    {
        http_response_code($code);
        $this->view("errors/{$code}", ['message' => $message]);
        exit;
    }
}