<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = 'users';
    protected $primaryKey = 'ID';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = ['Name', 'Email', 'Password'];
    
    // Add this relationship if you need to access posts from user
    public function posts() {
        return $this->hasMany('App\Model\Post', 'user_id', 'ID');
    }
}