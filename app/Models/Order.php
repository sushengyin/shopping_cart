<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'cart_id','total_price', 'status', 'is_shipped'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }


}
