<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
   
    
    protected $dates = ['deleted_at'];
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
        return $this->belongsTo('App\Model\User', 'user_id')
            ->withDefault([
                'name' => 'Deleted User'
            ]);
    }

    public function relatedPosts() {
        return $this->belongsToMany(Post::class, 'related_posts', 'post_id', 'related_post_id');
    }

    public function getRelatedPostsAttribute()
    {
        $firstRelations = $this->hasMany('App\Model\RelatedPost', 'post_1_id')->get();
        $secondRelations = $this->hasMany('App\Model\RelatedPost', 'post_2_id')->get();
        
        return $firstRelations->merge($secondRelations);
    }

    public function views()
    {
        return $this->hasMany('App\Model\PostView', 'post_id');
    }

    public function post1()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function post2()
    {
        return $this->belongsTo(Post::class, 'related_post_id');
}
}