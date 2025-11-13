<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

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
    
    public static function getTypeCounts()
    {
        $sellers = self::select('acc_type')->where('seller.verify', 1)->where('acc_type', '!=', '')->get();
        
        // Initialize default counts
        $counts = [
            1 => 0, // Seller
            2 => 0, // Contractor
            3 => 0, // Client
            4 => 0, // Buyer
        ];
        
        
        foreach ($sellers as $seller) {
            $types = explode(',', $seller->acc_type);
            foreach ($types as $type) {
                $type = trim($type);
                if (isset($counts[$type])) {
                    $counts[$type]++;
                }
            }
        }
        
        return [
            'seller'     => $counts[1],
            'contractor' => $counts[2],
            'client'     => $counts[3],
            'buyer'      => $counts[4],
        ];
    }
}
