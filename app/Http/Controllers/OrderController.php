<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $orderRepo;

    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::with('cartItems.product')->where('user_id', $user->id)->where('checkouted', false)->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart is empty or already checked out'], 400);
        }

        $totalPrice = $cart->cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        // dd($totalPrice);
        $order = $this->orderRepo->checkout($user, $cart, $totalPrice);
        // dd($cart);
        // 標記購物車已結帳
        $cart->update(['checkouted' => true]);
        return response()->json(['order' => $order], 201);
    }

    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->with('orderItems.product')->get();

        return response()->json($orders, 200);
    }

    public function show($orderId)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->where('id', $orderId)->with('orderItems.product')->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order, 200);
    }
}
