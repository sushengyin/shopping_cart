<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCartItem;
use App\Http\Requests\UpdateCartItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Repositories\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartRepo;

    public function __construct(CartRepository $cartRepo)
    {
        $this->middleware('auth:api'); // 確保用戶已經登入
        $this->cartRepo = $cartRepo;
    }

    /**
     * 建立購物車
     */
    public function createCart()
    {
        $user = Auth::user();
        $cart = $this->cartRepo->createCart($user->id);
        // $cart = $this->cartRepo->createCart(2);
        return response()->json(['cart' => $cart], 201);
    }

    /**
     * 添加商品到購物車
     */
    public function addToCart(CreateCartItem $request)
    {
        $user = Auth::user();
        // dd($request->validated());
        $cartItem = $this->cartRepo->addProductToCart($user->id, $request->validated());

        return response()->json(['cart_item' => $cartItem], 200);
    }

    /**
     * 讀取購物車內容
     */
    public function getCart()
    {
        $user = Auth::user();
        $cart = $this->cartRepo->getCartByUserId($user->id);

        return response()->json(['cart' => $cart], 200);
    }

    /**
     * 更新購物車項目數量
     */
    public function updateCartItem(UpdateCartItem $request, string $itemId)
    {

        $validatedData = $request->validated();
        // $user = Auth::user();
        // dd($request->all());
        $cartItem = $this->cartRepo->updateCartItem($itemId, $validatedData['quantity']);
        // echo($itemId);
        return response()->json(['cart_item' => $cartItem], 200);
    }

    /**
     * 刪除購物車項目
     */
    public function removeCartItem($itemId)
    {
        $user = Auth::user();
        $this->cartRepo->removeCartItem($user->id, $itemId);

        return response()->json(['message' => 'Cart item removed successfully'], 200);
    }
}
