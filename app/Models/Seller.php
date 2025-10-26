<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seller extends Authenticatable
{
    use HasFactory;

    protected $table = 'seller';

    protected $fillable = [
        'name',
        'hash_id',
        'email',
        'phone',
        'password',
        'gender',
        'client',
        'gst',
        'contractor',
        'buyer',
        'seller',
        'otp',
        'verify',
        'latitude',
        'longitude',
        'status',
        'ref_code',
        'ref_by',
        'acc_type',
        'pro_ser',
        'created_at',
        'lock_location',
    ];

    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = null;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // If you need to customize the authentication logic, you can override the getAuthPassword method
    // public function getAuthPassword()
    // {
    //     return $this->password;
    // }
}
