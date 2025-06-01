<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model 
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

    /**
     * User's posts relationship
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    /**
     * Related posts relationship
     */
    public function relatedPosts(): HasMany
    {
        return $this->hasMany(RelatedPost::class, 'post1_id');
    }

    /**
     * Post views relationship
     */
    public function postViews(): HasMany
    {
        return $this->hasMany(PostView::class, 'user_id');
    }

    /**
     * Automatically hash passwords
     */
    public function setPasswordAttribute($value): void
    {
        if (!empty($value)) {
            $this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT);
        }
    }

    /**
     * Verify user password
     */
    public function verifyPassword($password): bool
    {
        return password_verify($password, $this->password);
    }
}