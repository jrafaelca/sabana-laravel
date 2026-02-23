<?php

namespace Tests\Feature\Filament;

use App\Enums\ProductStatus;
use App\Filament\Resources\Products\Pages\ManageProducts;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class ProductsManagePageTest extends TestCase
{
    public function test_it_renders_products_table_and_can_search_records(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $cola = Product::query()->create([
            'name' => 'Cola',
            'slug' => 'cola',
            'description' => 'Bebida',
            'cost' => 1.00,
            'price' => 2.50,
            'status' => ProductStatus::Enabled,
        ]);

        $water = Product::query()->create([
            'name' => 'Agua',
            'slug' => 'agua',
            'description' => 'Agua mineral',
            'cost' => 0.50,
            'price' => 1.50,
            'status' => ProductStatus::Enabled,
        ]);

        $products = Product::query()->whereKey([$cola->id, $water->id])->get();

        Livewire::test(ManageProducts::class)
            ->assertCanSeeTableRecords($products)
            ->searchTable('Cola')
            ->assertCanSeeTableRecords($products->where('id', $cola->id))
            ->assertCanNotSeeTableRecords($products->where('id', $water->id));
    }
}
