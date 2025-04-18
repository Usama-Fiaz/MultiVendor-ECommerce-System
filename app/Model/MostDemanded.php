<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MostDemanded extends Model
{
    use HasFactory;
    protected $fillable = ['banner','product_id'];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
