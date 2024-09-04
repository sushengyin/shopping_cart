<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Services\OrderService;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    /**
     * 結帳並創建訂單
     *
     * @param \App\Models\User $user
     * @param \App\Models\Cart $cart
     * @param float $totalPrice
     * @return \App\Models\Order
     */
    public function checkout($user, $cart, $totalPrice)
    {
        $totalPrice = $this->orderService->calculateTotalPrice($user, $cart);

        $order = DB::transaction(function () use ($user, $cart, $totalPrice) {
            // 創建訂單
            $order = Order::create([
                'user_id' => $user->id,
                'cart_id' => $cart->id,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            // 創建訂單項目
            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            // 減少商品庫存
            $this->orderService->reduceProductStock($cart);

            // dd($cart);
            return $order;
        });

        return $order;
    }
}
