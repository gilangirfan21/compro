<?php

use App\Filament\Widgets\ContentStatsOverview;
use App\Filament\Widgets\LatestMessages;
use App\Filament\Widgets\MessagesChart;
use App\Models\Message;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Livewire\invade;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel('admin');
});

/**
 * @param  array<int, string>  $permissions
 */
function actingAsDashboardUser(array $permissions): User
{
    $user = User::factory()->create();
    $user->givePermissionTo(array_map(
        fn (string $name): Permission => Permission::findOrCreate($name, 'web'),
        $permissions,
    ));
    test()->actingAs($user);

    return $user;
}

it('shows content statistics', function () {
    actingAsDashboardUser(['ViewAny:Message', 'ViewAny:Post', 'ViewAny:Portfolio', 'ViewAny:Service']);

    Message::factory()->count(2)->create(['is_read' => false]);
    Message::factory()->create(['is_read' => true]);
    Post::factory()->count(3)->create();
    Post::factory()->draft()->create();
    Portfolio::factory()->inactive()->create();

    Livewire::test(ContentStatsOverview::class)
        ->assertOk()
        ->assertSeeInOrder(['Pesan belum dibaca', '2', '3 pesan dalam 7 hari terakhir'])
        ->assertSeeInOrder(['Berita terbit', '3', '4 total berita'])
        ->assertSeeInOrder(['Portofolio aktif', '0', '1 total portofolio']);
});

it('only shows statistics the user is allowed to see', function () {
    actingAsDashboardUser(['ViewAny:Post']);

    Livewire::test(ContentStatsOverview::class)
        ->assertSee('Berita terbit')
        ->assertDontSee('Pesan belum dibaca')
        ->assertDontSee('Layanan aktif');
});

it('hides message widgets from users who cannot view messages', function () {
    actingAsDashboardUser(['ViewAny:Post']);

    expect(MessagesChart::canView())->toBeFalse()
        ->and(LatestMessages::canView())->toBeFalse()
        ->and(ContentStatsOverview::canView())->toBeTrue();

    actingAsDashboardUser([]);

    expect(ContentStatsOverview::canView())->toBeFalse();
});

it('lists the five latest messages', function () {
    actingAsDashboardUser(['ViewAny:Message']);

    $old = Message::factory()->create(['created_at' => now()->subDays(10)]);
    $recent = Message::factory()->count(5)->create();

    Livewire::test(LatestMessages::class)
        ->assertOk()
        ->assertCanSeeTableRecords($recent)
        ->assertCanNotSeeTableRecords([$old]);
});

it('counts messages per month for the chart', function () {
    actingAsDashboardUser(['ViewAny:Message']);

    Message::factory()->count(2)->create();
    Message::factory()->create(['created_at' => now()->subMonthNoOverflow()]);
    Message::factory()->create(['created_at' => now()->subMonths(13)]);

    $data = invade(Livewire::test(MessagesChart::class)->assertOk()->instance())->getData();

    expect($data['datasets'][0]['data'])
        ->toHaveCount(12)
        ->and(array_sum($data['datasets'][0]['data']))->toBe(3)
        ->and(array_slice($data['datasets'][0]['data'], -2))->toBe([1, 2]);
});

it('renders the admin dashboard with every widget', function () {
    actingAsDashboardUser(['ViewAny:Message', 'ViewAny:Post', 'ViewAny:Portfolio', 'ViewAny:Service'])
        ->assignRole(Role::findOrCreate('editor', 'web'));

    $this->get(Filament::getPanel('admin')->getUrl())
        ->assertOk()
        ->assertSeeLivewire(ContentStatsOverview::class)
        ->assertSeeLivewire(MessagesChart::class)
        ->assertSeeLivewire(LatestMessages::class);
});

it('blocks users without a role from the admin panel', function () {
    actingAsDashboardUser(['ViewAny:Message']);

    $this->get(Filament::getPanel('admin')->getUrl())->assertForbidden();
});
