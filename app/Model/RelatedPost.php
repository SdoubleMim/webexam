<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RelatedPost extends Model {
    protected $table = 'related_posts';
    protected $fillable = ['post_1_id', 'post_2_id'];
    public $timestamps = true;

    public function post1() {
        return $this->belongsTo(Post::class, 'post_1_id');
    }

    public function post2() {
        return $this->belongsTo(Post::class, 'post_2_id');
    }
}