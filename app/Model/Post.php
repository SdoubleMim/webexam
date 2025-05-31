<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\{User, PostView};

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
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relationship to User model
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'ID')
            ->withDefault([
                'Name' => 'Deleted User'
            ]);
    }

    /**
     * Related posts relationship
     */
    public function relatedPosts() {
        return $this->belongsToMany(
            Post::class, 
            'related_posts', 
            'post_1_id', 
            'post_2_id'
        )->withTimestamps();
    }

    /**
     * Post views relationship
     */
    public function postViews() {
        return $this->hasMany(PostView::class, 'post_id');
    }
}