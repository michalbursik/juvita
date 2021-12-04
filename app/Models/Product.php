<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    const DEFAULT_UNIT = 'kg';
    const AVAILABLE_UNITS = ['kg', 'ks'];
}
