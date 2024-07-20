<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use App\Models\group;

class group_info extends Model
{
    use HasFactory,SoftDeletes,HasApiTokens;

    protected $fillable = [
        'group_id',
        'name',
        'paid',
        'amm_to_paid',
        'paidby',
        'isPaid',
    ];
}
