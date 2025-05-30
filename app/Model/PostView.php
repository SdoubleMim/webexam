<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PostView extends Model {
    protected $table = 'post_views';
    protected $fillable = ['post_id', 'views'];
    public $timestamps = true;

    public function post() {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
