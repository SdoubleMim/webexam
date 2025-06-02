<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class RelatedPost extends Model
{
    protected $table = 'related_posts';
    protected $fillable = ['post_1_id', 'post_2_id'];
    
    public function post1()
    {
        return $this->belongsTo(Post::class, 'post_1_id')->withDefault([
            'title' => '[Delated Post]',
            'user' => (object)['name' => 'Unknown']
        ]);
    }
    
    public function post2()
    {
        return $this->belongsTo(Post::class, 'post_2_id')->withDefault([
            'title' => '[Delated Post]',
            'user' => (object)['name' => 'Unknown']
        ]);
    }
    
    public static function generateRandomRelations()
    {
        $posts = Post::all();
        
        if ($posts->count() < 2) {
            return false;
        }
        
        foreach ($posts as $post) {
            $relatedCount = rand(3, 5);
            $relatedPosts = $posts->where('id', '!=', $post->id)
                               ->random(min($relatedCount, $posts->count() - 1));
            
            foreach ($relatedPosts as $related) {
                // جلوگیری از روابط تکراری
                $exists = self::where(function($q) use ($post, $related) {
                    $q->where('post_1_id', $post->id)
                      ->where('post_2_id', $related->id);
                })->orWhere(function($q) use ($post, $related) {
                    $q->where('post_1_id', $related->id)
                      ->where('post_2_id', $post->id);
                })->exists();
                
                if (!$exists) {
                    self::create([
                        'post_1_id' => $post->id,
                        'post_2_id' => $related->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
            }
        }
        
        return true;
    }
}