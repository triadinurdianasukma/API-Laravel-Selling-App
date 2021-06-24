<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use HasFactory, SoftDeletes;

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

        //membuat relasi table antara product categories dan product
        public function products()
        {
            //key antara table product dan product categories adalah 'categories_id' (table product) 
            // dan 'id'(table product categories)
            //relasinya menggunakan 1 to many
            return $this->hasMany(Product::class, 'categories_id', 'id');
        }
}
