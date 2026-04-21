<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    protected $fillable = ['non_vegan_id', 'vegan_id'];
}