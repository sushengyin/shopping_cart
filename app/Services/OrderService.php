<?php

namespace App\Services;

use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * 計算訂單的總價，並應用VIP優惠
     *
     * @param User $user
     * @param Cart $cart
     * @return float
     */
    public function calculateTotalPrice(User $user, Cart $cart)
    {
        $totalPrice = 0;

        foreach ($cart->cartItems as $item) {
            // 商品數量檢查防呆
            $product = Product::findOrFail($item->product_id);

            if ($item->quantity > $product->quantity) {
                throw new \Exception("The quantity of {$product->title} exceeds available stock.");
            }

            // 計算單個商品的總價
            $itemTotal = $item->quantity * $product->price;

            // 檢查是否為VIP，並應用8折優惠
            if ($user->level == 2) { // 假設level 2是VIP
                $itemTotal *= 0.8; // 8折優惠
            }

            $totalPrice += $itemTotal;
        }

        return $totalPrice;
    }

    /**
     * 減少商品的庫存數量
     *
     * @param Cart $cart
     * @return void
     */
    public function reduceProductStock(Cart $cart)
    {
        foreach ($cart->cartItems as $item) {
            $product = Product::findOrFail($item->product_id);

            // 減少庫存數量
            $product->quantity -= $item->quantity;

            // 保存商品庫存變化
            $product->save();
        }
    }
}
