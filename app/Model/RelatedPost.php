<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RelatedPost extends Model 
{
    protected $table = 'related_posts';
    protected $fillable = ['post_1_id', 'post_2_id']; // اصلاح نام فیلدها مطابق دیتابیس
    public $timestamps = true; // تغییر به true چون جدول شما دارای created_at و updated_at است

    public static function generateRandomRelations()
    {
        $posts = Post::all();
        
        foreach ($posts as $post) {
            $count = rand(1, 3);
            $relatedPosts = Post::where('id', '!=', $post->id)
                            ->inRandomOrder()
                            ->take($count)
                            ->get();
            
            foreach ($relatedPosts as $relatedPost) {
                self::firstOrCreate([
                    'post_1_id' => $post->id,
                    'post_2_id' => $relatedPost->id
                ]);
            }
        }
    }

    public function post1()
    {
        return $this->belongsTo(Post::class, 'post_1_id');
    }

    public function post2()
    {
        return $this->belongsTo(Post::class, 'post_2_id');
    }
}