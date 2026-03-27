<?php

use App\Models\Address;
use App\Models\Orders;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Surfsidemedia\Shoppingcart\Facades\Cart;

uses(RefreshDatabase::class);

function makeUser(string $type = 'USR', ?string $email = null): User
{
    static $counter = 1;

    $current = $counter++;
    $email ??= 'user' . $current . '@example.com';

    $user = User::create([
        'name' => 'User ' . $current,
        'email' => $email,
        'mobile' => '900000' . str_pad((string) $current, 4, '0', STR_PAD_LEFT),
        'password' => Hash::make('password'),
    ]);

    $user->forceFill(['utype' => $type])->save();

    return $user->fresh();
}

function makeOrderFor(User $user): Orders
{
    $order = new Orders();
    $order->user_id = $user->id;
    $order->subtotal = 100;
    $order->discount = 0;
    $order->tax = 10;
    $order->total = 110;
    $order->name = $user->name;
    $order->phone = '900000000';
    $order->locality = 'Lome';
    $order->address = 'Rue 1';
    $order->city = 'Lome';
    $order->state = 'Maritime';
    $order->country = 'Togo';
    $order->landmark = 'Market';
    $order->zip = '12345';
    $order->status = 'ordered';
    $order->save();

    return $order;
}

function makeAddressFor(User $user): Address
{
    return Address::create([
        'user_id' => $user->id,
        'name' => $user->name,
        'phone' => '900000000',
        'locality' => 'Lome',
        'address' => 'Rue 1',
        'city' => 'Lome',
        'state' => 'Maritime',
        'country' => 'Togo',
        'landmark' => 'Market',
        'zip' => '12345',
        'type' => 'home',
        'is_default' => true,
    ]);
}

function makeProduct(array $overrides = []): Product
{
    return Product::create(array_merge([
        'name' => 'Product demo',
        'slug' => 'product-demo-' . uniqid(),
        'short_description' => 'Short description',
        'description' => 'Long description',
        'regular_price' => 100,
        'sale_price' => null,
        'SKU' => 'SKU-' . uniqid(),
        'stock_status' => 'instock',
        'featured' => false,
        'quantity' => 5,
        'image' => 'product.jpg',
        'images' => null,
        'category_id' => null,
        'brand_id' => null,
    ], $overrides));
}

test('non admin users cannot access admin routes', function () {
    $user = makeUser('USR');

    $this->actingAs($user)
        ->get(route('admin.search', ['query' => 'demo']))
        ->assertForbidden();
});

test('non admin users cannot access admin order listing', function () {
    $user = makeUser('USR', 'user-orders@example.com');

    $this->actingAs($user)
        ->get(route('admin.orders'))
        ->assertForbidden();
});

test('admin users can access admin routes', function () {
    $admin = makeUser('ADM', 'admin@example.com');

    $this->actingAs($admin)
        ->get(route('admin.search', ['query' => 'demo']))
        ->assertOk();
});

test('admin users can access admin order details', function () {
    $admin = makeUser('ADM', 'admin-orders@example.com');
    $owner = makeUser('USR', 'owner-orders@example.com');
    $order = makeOrderFor($owner);

    $this->actingAs($admin)
        ->get(route('admin.order.details', ['order_id' => $order->id]))
        ->assertOk();
});

test('a user cannot cancel another users order', function () {
    $owner = makeUser('USR', 'owner@example.com');
    $attacker = makeUser('USR', 'attacker@example.com');
    $order = makeOrderFor($owner);

    $this->actingAs($attacker)
        ->put(route('user.order.cancel'), ['order_id' => $order->id])
        ->assertNotFound();

    expect($order->fresh()->status)->toBe('ordered');
});

test('a user cannot edit another users address', function () {
    $owner = makeUser('USR', 'owner2@example.com');
    $attacker = makeUser('USR', 'attacker2@example.com');
    $address = makeAddressFor($owner);

    $this->actingAs($attacker)
        ->get(route('user.addresses.edit', $address))
        ->assertForbidden();
});

test('a user cannot delete another users address', function () {
    $owner = makeUser('USR', 'owner3@example.com');
    $attacker = makeUser('USR', 'attacker3@example.com');
    $address = makeAddressFor($owner);

    $this->actingAs($attacker)
        ->delete(route('user.addresses.destroy', $address))
        ->assertForbidden();

    expect(Address::find($address->id))->not->toBeNull();
});

test('admin order details returns 404 for unknown orders', function () {
    $admin = makeUser('ADM', 'admin2@example.com');

    $this->actingAs($admin)
        ->get(route('admin.order.details', ['order_id' => 999999]))
        ->assertNotFound();
});

test('cart uses the server side product price instead of client supplied values', function () {
    Cart::instance('cart')->destroy();
    $user = makeUser('USR', 'buyer@example.com');
    $product = makeProduct(['regular_price' => 250, 'sale_price' => 199]);

    $this->actingAs($user)
        ->post(route('cart.add'), [
            'id' => $product->id,
            'name' => 'Hacked product name',
            'price' => 1,
            'quantity' => 1,
        ])
        ->assertRedirect();

    $item = Cart::instance('cart')->content()->first();

    expect($item)->not->toBeNull()
        ->and((int) $item->id)->toBe($product->id)
        ->and($item->name)->toBe($product->name)
        ->and((float) $item->price)->toBe(199.0);
});

test('cart rejects quantities above available stock', function () {
    Cart::instance('cart')->destroy();
    $user = makeUser('USR', 'buyer2@example.com');
    $product = makeProduct(['quantity' => 2]);

    $this->actingAs($user)
        ->post(route('cart.add'), [
            'id' => $product->id,
            'quantity' => 3,
        ])
        ->assertSessionHas('error');

    expect(Cart::instance('cart')->count())->toBe(0);
});

test('admin order status rejects invalid values', function () {
    $admin = makeUser('ADM', 'admin3@example.com');
    $owner = makeUser('USR', 'buyer3@example.com');
    $order = makeOrderFor($owner);

    $this->actingAs($admin)
        ->from(route('admin.order.details', ['order_id' => $order->id]))
        ->put(route('update.order.status'), [
            'order_id' => $order->id,
            'order_status' => 'refunded',
        ])
        ->assertSessionHasErrors('order_status');

    expect($order->fresh()->status)->toBe('ordered');
});

test('admin account password update requires the current password', function () {
    $admin = makeUser('ADM', 'admin-password@example.com');

    $this->actingAs($admin)
        ->from(route('admin.account.edit'))
        ->post(route('admin.account.update'), [
            'name' => $admin->name,
            'mobile' => $admin->mobile,
            'email' => $admin->email,
            'old_password' => 'wrong-password',
            'new_password' => 'NewPassword123!',
            'new_password_confirmation' => 'NewPassword123!',
        ])
        ->assertSessionHasErrors('old_password');

    expect(Hash::check('password', $admin->fresh()->password))->toBeTrue();
});
