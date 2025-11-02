<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seller extends Authenticatable
{
    use HasFactory;

    protected $table = 'seller';

    protected $fillable = [
        'name',
        'email',
        'hash_id',
        'phone',
        'password',
        'user_type',
        'pancard',
        'gst',
        'otp',
        'verify',
        'gender',
    ];

    // If you need to customize the authentication logic, you can override the getAuthPassword method
    // public function getAuthPassword()
    // {
    //     return $this->password; 
    // }
}
