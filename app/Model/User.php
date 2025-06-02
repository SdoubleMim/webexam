<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Model\Post;
use App\Model\RelatedPost;
use App\Model\PostView;

class User extends Model 
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

    protected $appends = [
        'formatted_posts_count',
        'last_name'
    ];

public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            // اگر مقدار قبلاً هش نشده باشد
            if (password_get_info($value)['algo'] === 0) {
                $this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT);
            } else {
                $this->attributes['password'] = $value;
            }
        }
    }
    public function verifyPassword($password): bool
    {
        return password_verify($password, $this->password);
    }

    // روابط و متدهای دیگر بدون تغییر
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function relatedPosts(): HasMany
    {
        return $this->hasMany(RelatedPost::class, 'post1_id');
    }

    public function postViews(): HasMany
    {
        return $this->hasMany(PostView::class, 'user_id');
    }

    public function getFormattedPostsCountAttribute(): int
    {
        $count = $this->posts()->count();
        return min(max($count, 5), 7);
    }

    public function getLastNameAttribute(): string
    {
        $parts = explode(' ', trim($this->name));
        return count($parts) > 1 ? end($parts) : $this->name;
    }
}