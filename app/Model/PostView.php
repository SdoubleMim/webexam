<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PostView extends Model 
{
    protected $table = 'post_views';
    protected $fillable = ['post_id', 'ip_address'];
    public $timestamps = true;

    public static function recordView($postId)
    {
        self::create([
            'post_id' => $postId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
        ]);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}