<?php

namespace App\Models;

use App\Traits\FilterScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    use FilterScope;

    protected $fillable = ['name', 'description', 'amount', 'quantity'];
}
