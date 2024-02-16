<?php
//@noramknarf (Francis Moran) - Everything
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'product_name',
        'product_price',
        'image_path',
        'RAM',
        'GPU',
        'processor'
    ];
}
