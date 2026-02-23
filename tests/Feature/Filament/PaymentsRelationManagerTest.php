<?php

namespace Tests\Feature\Filament;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethods;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\RelationManagers\PaymentsRelationManager;
use App\Filament\Resources\Products\ProductResource;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Livewire\Livewire;
use Tests\TestCase;

class PaymentsRelationManagerTest extends TestCase
{
    public function test_it_renders_payment_relation_manager_table_records(): void
    {
        $creator = User::factory()->create();
        $this->actingAs($creator);

        $order = Order::query()->create([
            'status' => OrderStatus::Pending,
            'notes' => 'Mesa 11',
            'total' => 0,
            'creator_id' => $creator->id,
            'server_id' => $creator->id,
        ]);

        $firstPayment = Payment::query()->create([
            'reference' => 'REL-001',
            'method' => PaymentMethods::Cash,
            'amount' => 10.00,
            'note' => 'Inicial',
            'order_id' => $order->id,
            'creator_id' => $creator->id,
        ]);

        $secondPayment = Payment::query()->create([
            'reference' => 'REL-002',
            'method' => PaymentMethods::DebitCard,
            'amount' => 5.50,
            'note' => 'Segundo pago',
            'order_id' => $order->id,
            'creator_id' => $creator->id,
        ]);

        $payments = Payment::query()->whereKey([$firstPayment->id, $secondPayment->id])->get();

        Livewire::test(PaymentsRelationManager::class, [
            'ownerRecord' => $order,
            'pageClass' => EditOrder::class,
        ])
            ->assertCanSeeTableRecords($payments);
    }

    public function test_it_configures_relation_manager_form_and_infolist_schemas(): void
    {
        $creator = User::factory()->create();
        $this->actingAs($creator);

        $order = Order::query()->create([
            'status' => OrderStatus::Pending,
            'notes' => 'Mesa 12',
            'total' => 0,
            'creator_id' => $creator->id,
            'server_id' => $creator->id,
        ]);

        $payment = Payment::query()->create([
            'reference' => 'REL-REF',
            'method' => PaymentMethods::Cash,
            'amount' => 1.25,
            'note' => 'Visible',
            'order_id' => $order->id,
            'creator_id' => $creator->id,
        ]);

        $livewire = Livewire::test(PaymentsRelationManager::class, [
            'ownerRecord' => $order,
            'pageClass' => EditOrder::class,
        ]);

        /** @var PaymentsRelationManager $manager */
        $manager = $livewire->instance();

        $formSchema = $manager->form(Schema::make($manager));
        $infolistSchema = $manager->infolist(Schema::make($manager)->record($payment));
        $payment->delete();
        $infolistSchemaForTrashedRecord = $manager->infolist(Schema::make($manager)->record($payment->fresh()));
        $configuredTable = $manager->table(Table::make($manager));

        $this->assertCount(4, $formSchema->getComponents());
        $this->assertCount(8, $infolistSchema->getComponents());
        $this->assertCount(8, $infolistSchemaForTrashedRecord->getComponents());
        $this->assertCount(8, $configuredTable->getColumns());
    }

    public function test_it_creates_a_payment_via_relation_manager_create_action(): void
    {
        $creator = User::factory()->create();
        $this->actingAs($creator);

        $order = Order::query()->create([
            'status' => OrderStatus::Pending,
            'notes' => 'Mesa 13',
            'total' => 0,
            'creator_id' => $creator->id,
            'server_id' => $creator->id,
        ]);

        Livewire::test(PaymentsRelationManager::class, [
            'ownerRecord' => $order,
            'pageClass' => EditOrder::class,
        ])
            ->assertTableHeaderActionsExistInOrder(['create'])
            ->callTableAction('create', null, [
                'reference' => 'REL-003',
                'method' => PaymentMethods::CreditCard->value,
                'amount' => 9.99,
                'note' => 'Creado por accion',
            ])
            ->assertHasNoErrors();

        $payment = Payment::query()->where('reference', 'REL-003')->firstOrFail();

        $this->assertSame($order->id, $payment->order_id);
        $this->assertSame($creator->id, $payment->creator_id);
        $this->assertSame(PaymentMethods::CreditCard, $payment->method);
    }

    public function test_product_resource_record_binding_query_includes_soft_deleted_records(): void
    {
        $product = Product::query()->create([
            'name' => 'Producto oculto',
            'slug' => 'producto-oculto',
            'description' => 'Soft deleted',
            'cost' => 1.00,
            'price' => 2.00,
            'status' => \App\Enums\ProductStatus::Enabled,
        ]);

        $product->delete();

        $resolved = ProductResource::getRecordRouteBindingEloquentQuery()
            ->whereKey($product->id)
            ->first();

        $this->assertNotNull($resolved);
        $this->assertTrue($resolved->trashed());
    }
}
