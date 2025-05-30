<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = 'users';
    protected $fillable = ['ID', 'Name', 'Email', 'Password'];
    protected $primaryKey = 'ID';
    public $incrementing = true;
    public $timestamps = false;
}
