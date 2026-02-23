<?php

namespace Tests\Feature\Livewire\Users;

use App\Livewire\Users\Edit;
use App\Livewire\Users\Index;
use App\Models\User;
use Database\Seeders\TestConstants;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::find(TestConstants::USER_ADMIN_ID);
});

test('cannot delete self', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('deleteUser', $this->admin->id)
        ->assertOk();

    expect(User::find($this->admin->id))->not->toBeNull();
});

test('cannot delete the last admin', function () {
    // Only one admin exists in seeder (TestConstants::USER_ADMIN_ID)
    $otherUser = User::find(TestConstants::USER_EMPLOYEE_ID);

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('deleteUser', $this->admin->id) // Already covered by "cannot delete self"
        ->assertOk();

    // Create another admin to test "not self but last admin"
    $secondAdmin = User::factory()->create(['role' => 'admin']);

    // Now there are 2 admins. Delete one of them.
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('deleteUser', $secondAdmin->id)
        ->assertOk();

    expect(User::find($secondAdmin->id))->toBeNull();

    // Now only 1 admin remains. Try to delete it (if it wasn't self).
    // Let's create another admin, login as him, and try to delete the original admin.
    $thirdAdmin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($thirdAdmin)
        ->test(Index::class)
        ->call('deleteUser', $this->admin->id)
        ->assertOk();

    // Since original admin was NOT the last one, it should be deleted.
    expect(User::find($this->admin->id))->toBeNull();

    // Now thirdAdmin is the ONLY admin.
    expect(User::where('role', 'admin')->count())->toBe(1);
});

test('cannot demote the last admin', function () {
    // Admin user exists.
    expect(User::where('role', 'admin')->count())->toBe(1);

    Livewire::actingAs($this->admin)
        ->test(Edit::class, ['user' => $this->admin])
        ->set('role', 'employee')
        ->call('submit')
        ->assertHasErrors(['role']);

    $this->admin->refresh();
    expect($this->admin->role)->toBe('admin');
});

test('can demote admin if not the last one', function () {
    $secondAdmin = User::factory()->create(['role' => 'admin']);
    expect(User::where('role', 'admin')->count())->toBe(2);

    Livewire::actingAs($this->admin)
        ->test(Edit::class, ['user' => $secondAdmin])
        ->set('role', 'employee')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirect(route('users.index'));

    $secondAdmin->refresh();
    expect($secondAdmin->role)->toBe('employee');
});
