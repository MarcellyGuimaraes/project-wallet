<?php

namespace Tests\Feature;

use App\Livewire\Admin\Users;
use App\Livewire\Auth\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_the_admin_users_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin/users')->assertForbidden();
    }

    public function test_admin_can_block_and_unblock_a_user(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $target = User::factory()->create();

        $this->actingAs($admin);

        Livewire::test(Users::class)
            ->call('toggleBlock', $target->id)
            ->assertSet('successMessage', "Conta de {$target->name} bloqueada.");

        $this->assertTrue($target->fresh()->isBlocked());

        Livewire::test(Users::class)
            ->call('toggleBlock', $target->id)
            ->assertSet('successMessage', "Conta de {$target->name} desbloqueada.");

        $this->assertFalse($target->fresh()->isBlocked());
    }

    public function test_admin_cannot_block_another_admin(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $otherAdmin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        Livewire::test(Users::class)
            ->call('toggleBlock', $otherAdmin->id)
            ->assertSet('errorMessage', 'Não é possível bloquear outro administrador.');

        $this->assertFalse($otherAdmin->fresh()->isBlocked());
    }

    public function test_admin_cannot_block_their_own_account(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        Livewire::test(Users::class)
            ->call('toggleBlock', $admin->id)
            ->assertSet('errorMessage', 'Você não pode bloquear a própria conta.');

        $this->assertFalse($admin->fresh()->isBlocked());
    }

    public function test_a_blocked_user_cannot_log_in(): void
    {
        $user = User::factory()->create(['blocked_at' => now()]);

        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'password')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }
}
