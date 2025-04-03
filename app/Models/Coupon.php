<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['business_id', 'title', 'regular_price', 'offer_price', 'start_date',
  'end_date'
];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}