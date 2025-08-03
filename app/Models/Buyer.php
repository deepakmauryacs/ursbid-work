<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buyer extends Authenticatable
{
    use HasFactory;

    protected $table = 'buyer';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'gender',
    ];

    // If you need to customize the authentication logic, you can override the getAuthPassword method
    // public function getAuthPassword()
    // {
    //     return $this->password; 
    // }
}
