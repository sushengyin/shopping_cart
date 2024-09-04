<?php
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_to_cart()
    {
        // 假設你有一個工廠來生成使用者
        $user = User::find(1);
        // dd($user);
        // 假裝使用者已登入
        $this->actingAs($user, 'api');

        // 模擬一個產品
        $product = Product::factory()->create();

        // 發送 POST 請求到添加購物車 API
        $response = $this->postJson('/api/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        // 驗證請求是否成功
        $response->assertStatus(200);
        $response->assertJson([
            'cart_item' => [
                'product_id' => $product->id,
                'quantity' => 2,
            ],
        ]);
    }
}
