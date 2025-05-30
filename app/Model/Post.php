<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {
    protected $table = 'posts';
    protected $fillable = ['title', 'content', 'user_id'];
    
    public function user() {
        return $this->belongsTo('App\Model\User', 'user_id');
    }
    
    public function relatedPosts() {
        return $this->belongsToMany('App\Model\Post', 'related_posts', 'post_1_id', 'post_2_id');
    }

    public function postViews() {
    return $this->hasMany(PostView::class, 'post_id');
}

}