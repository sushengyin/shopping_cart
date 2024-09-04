<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartRepository
{
    /**
     * 創建新購物車
     */
    public function createCart($userId)
    {
        return Cart::create(['user_id' => $userId, 'checkouted' => false]);
    }

    /**
     * 添加商品到購物車
     */
    public function addProductToCart($userId, $data)
    {
        $cart = Cart::firstOrCreate(['user_id' => $userId, 'checkouted' => false]);

        // 檢查商品是否已存在於購物車中
        $cartItem = $cart->cartItems()->where('product_id', $data['product_id'])->first();

        if ($cartItem) {
            $cartItem->quantity += $data['quantity'];
            $cartItem->save();
        } else {
            $cartItem = $cart->cartItems()->create([
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity'],
            ]);
        }

        return $cartItem;
    }

    /**
     * 根據用戶ID獲取購物車
     */
    public function getCartByUserId($userId)
    {
        return Cart::with('cartItems.product')->where('user_id', $userId)->where('checkouted', false)->first();
    }

    /**
     * 更新購物車項目
     */
    public function updateCartItem($itemId, $quantity)
    {
        $cartItem = CartItem::find($itemId);

        $cartItem->update(['quantity' => $quantity]);

        return $cartItem;
    }

    /**
     * 刪除購物車項目
     */
    public function removeCartItem($userId, $itemId)
    {
        $cartItem = CartItem::whereHas('cart', function ($query) use ($userId) {
            $query->where('user_id', $userId)->where('checkouted', false);
        })->findOrFail($itemId);

        $cartItem->delete();
    }
}
