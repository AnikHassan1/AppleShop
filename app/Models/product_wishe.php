<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class product_wishe extends Model
{
    protected $fillable = ['product_id','user_id'];
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
