<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerSupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'attachment',
        'status',
    ];

    /**
     * Get the seller who created the ticket.
     */
    public function creator()
    {
        return $this->belongsTo(Seller::class, 'user_id');
    }
}
