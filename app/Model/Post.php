<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\{User, PostView, RelatedPost};

class Post extends Model {
    protected $table = 'posts';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    
    protected $fillable = [
        'title', 
        'content', 
        'user_id'
    ];
    
    public function user() {
        return $this->belongsTo(User::class, 'user_id')
            ->withDefault([
                'name' => 'Deleted User'
            ]);
    }

    public function relatedPosts()
    {
        return $this->belongsToMany(Post::class, 'related_posts', 'post_id', 'related_post_id')
                    ->withTimestamps();
    }

    public function views()
    {
        return $this->hasMany(PostView::class, 'post_id');
    }
}