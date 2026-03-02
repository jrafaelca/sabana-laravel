<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class UsersManagePageTest extends TestCase
{
    public function test_it_renders_users_table_and_can_search_records(): void
    {
        $authenticatedUser = User::factory()->create();
        $this->actingAs($authenticatedUser);

        $ana = User::factory()->create([
            'name' => 'Ana Perez',
            'email' => 'ana@example.com',
        ]);

        $carlos = User::factory()->create([
            'name' => 'Carlos Rojas',
            'email' => 'carlos@example.com',
        ]);

        $users = User::query()->whereKey([$ana->id, $carlos->id])->get();

        Livewire::test(ManageUsers::class)
            ->assertCanSeeTableRecords($users)
            ->searchTable('Ana')
            ->assertCanSeeTableRecords($users->where('id', $ana->id))
            ->assertCanNotSeeTableRecords($users->where('id', $carlos->id));
    }
}
