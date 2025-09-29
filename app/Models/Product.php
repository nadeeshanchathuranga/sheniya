<?php

namespace App\Models;

use App\Traits\GeneratesUniqueCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory, GeneratesUniqueCode;
    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'code',
        'size_id',
        'discount',
        'discounted_price',
        'color_id',
        'cost_price',
        'selling_price',
        'stock_quantity',
        'barcode',
        'image',
        'expire_date',
        'batch_no',
        'total_quantity',
        'purchase_date',
        'is_promotion',
    ];

    // public static function boot()
    // {
    //     parent::boot();

    //     // Automatically generate a unique code when creating an order
    //     static::creating(function ($model) {
    //         $model->barcode = $model->generateUniqueCode(12);
    //     });
    // }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id','id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id','id');
    }


    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id','id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id','id');
    }

    protected $casts = [
        'expire_date' => 'date', // Cast expiry_date as a date
    ];

      public function promotionItems()
    {
        return $this->hasMany(PromotionItem::class, 'promotion_id');
    }

    public function components() // many Product via pivot with qty
    {
        return $this->belongsToMany(
            Product::class,
            'promotion_items',
            'promotion_id', // this product (pack)
            'product_id'    // component
        )->withPivot('quantity')->withTimestamps();
    }

    // packs that include THIS product as a component
    public function includedInPromotions()
    {
        return $this->belongsToMany(
            Product::class,
            'promotion_items',
            'product_id',    // this product as component
            'promotion_id'   // pack product
        )->withPivot('quantity');
    }


}
