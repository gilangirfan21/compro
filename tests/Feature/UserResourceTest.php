<?php

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel('admin');

    $this->role = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);

    foreach (['ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny'] as $ability) {
        $this->role->givePermissionTo(Permission::create(['name' => "{$ability}:User", 'guard_name' => 'web']));
    }

    $this->admin = User::factory()->create();
    $this->admin->assignRole($this->role);

    $this->actingAs($this->admin);
});

it('lists users with role badges', function () {
    Livewire::test(ListUsers::class)
        ->assertOk()
        ->assertCanSeeTableRecords([$this->admin])
        ->assertSee('super_admin');
});

it('requires password and unique email on create', function () {
    Livewire::test(CreateUser::class)
        ->fillForm(['name' => 'Test', 'email' => $this->admin->email, 'password' => ''])
        ->call('create')
        ->assertHasFormErrors(['email' => 'unique', 'password' => 'required']);
});

it('creates a user with hashed password and roles', function () {
    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'secret123',
            'roles' => [$this->role->id],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $user = User::where('email', 'test@example.com')->firstOrFail();

    expect(Hash::check('secret123', $user->password))->toBeTrue()
        ->and($user->hasRole('super_admin'))->toBeTrue();
});

it('keeps the existing password when left blank on edit', function () {
    $user = User::factory()->create(['password' => 'oldpass123']);
    $hash = $user->password;

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->fillForm(['name' => 'Renamed', 'password' => ''])
        ->call('save')
        ->assertHasNoFormErrors();

    $user->refresh();

    expect($user->name)->toBe('Renamed')
        ->and($user->password)->toBe($hash);
});

it('updates the password when filled on edit', function () {
    $user = User::factory()->create();

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->fillForm(['password' => 'newpass123'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(Hash::check('newpass123', $user->refresh()->password))->toBeTrue();
});
