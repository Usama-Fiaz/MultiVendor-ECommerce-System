<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddFundBonusCategories extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function scopeActive($query)
    {

        return $query->where(['is_active' => 1]);
    }
}
