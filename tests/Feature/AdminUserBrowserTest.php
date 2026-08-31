<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\Admin\UserIndex;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use Tests\TestCase;

class AdminUserBrowserTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_cannot_assign_themselves_an_admin_role(): void
    {
        $user = User::create([
            'name' => 'Normaler Nutzer',
            'email' => 'normal@example.test',
            'password' => 'password',
            'role' => UserRole::Admin,
        ]);

        $this->assertSame(UserRole::User, $user->role);
        $this->assertFalse($user->isAdmin());
    }

    public function test_guests_are_redirected_from_every_admin_page(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->for($user)->create();

        foreach ([
            route('admin.users.index'),
            route('admin.users.show', ['userId' => $user->id]),
            route('admin.users.recipes.show', ['userId' => $user->id, 'recipeId' => $recipe->id]),
        ] as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
    }

    public function test_normal_users_cannot_access_admin_pages_or_components(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->for($user)->create();

        $this->actingAs($user);

        $this->get(route('admin.users.index'))->assertForbidden();
        $this->get(route('admin.users.show', ['userId' => $user->id]))->assertForbidden();
        $this->get(route('admin.users.show', ['userId' => 999999]))->assertForbidden();
        $this->get(route('admin.users.recipes.show', ['userId' => $user->id, 'recipeId' => $recipe->id]))->assertForbidden();
        $this->get(route('admin.users.recipes.show', ['userId' => $user->id, 'recipeId' => 999999]))->assertForbidden();

        Livewire::test(UserIndex::class)->assertForbidden();
    }

    public function test_an_admin_can_search_users_by_name_or_email(): void
    {
        $admin = User::factory()->admin()->create(['name' => 'Veganizer Admin']);
        $lisa = User::factory()->create([
            'name' => 'Lisa Muster',
            'email' => 'lisa@example.test',
        ]);
        $mehmet = User::factory()->create([
            'name' => 'Mehmet Beispiel',
            'email' => 'mehmet@example.test',
        ]);
        Recipe::factory()->for($lisa)->count(2)->create();

        $this->actingAs($admin);

        Livewire::test(UserIndex::class)
            ->set('search', '  LISA  ')
            ->assertSee('Lisa Muster')
            ->assertSee('lisa@example.test')
            ->assertSee('2')
            ->assertDontSee('Mehmet Beispiel')
            ->call('clearSearch')
            ->assertSee('Mehmet Beispiel');

        Livewire::test(UserIndex::class)
            ->set('search', 'mehmet@example')
            ->assertSee($mehmet->email)
            ->assertDontSee($lisa->email);
    }

    public function test_admin_list_shows_safe_account_data_and_admin_navigation(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create([
            'name' => 'Sicherer Account',
            'email' => 'sicher@example.test',
            'remember_token' => 'SECRET-REMEMBER-TOKEN',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Sicherer Account')
            ->assertSee('sicher@example.test')
            ->assertSee('Admin')
            ->assertDontSee($user->password, false)
            ->assertDontSee('SECRET-REMEMBER-TOKEN');

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee(route('admin.users.index'));
    }

    public function test_user_pagination_uses_german_labels_and_can_change_pages(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->count(16)->create();

        $this->actingAs($admin);

        Livewire::test(UserIndex::class)
            ->assertSee('Weiter')
            ->assertDontSee('pagination.next')
            ->call('nextPage')
            ->assertSee('Zurück')
            ->assertDontSee('pagination.previous');
    }

    public function test_admin_profile_only_lists_active_recipes_from_the_selected_user(): void
    {
        $admin = User::factory()->admin()->create();
        $subject = User::factory()->create([
            'name' => 'Lisa Muster',
            'email' => 'lisa@example.test',
        ]);
        $other = User::factory()->create();

        $visibleRecipe = Recipe::factory()->for($subject)->create(['title' => 'Lisas Carbonara']);
        $visibleRecipe->ingredients()->create(['name' => 'Spaghetti', 'position' => 0]);
        $deletedRecipe = Recipe::factory()->for($subject)->create(['title' => 'Gelöschtes Rezept']);
        $deletedRecipe->delete();
        Recipe::factory()->for($other)->create(['title' => 'Fremdes Rezept']);

        $this->actingAs($admin)
            ->get(route('admin.users.show', ['userId' => $subject->id]))
            ->assertOk()
            ->assertSee('Lisa Muster')
            ->assertSee('lisa@example.test')
            ->assertSee('Lisas Carbonara')
            ->assertSee('1 Zutat')
            ->assertDontSee('Gelöschtes Rezept')
            ->assertDontSee('Fremdes Rezept');
    }

    public function test_admin_can_inspect_a_scoped_recipe_but_cannot_edit_it_through_user_pages(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->create(['name' => 'Rezeptbesitzer']);
        $recipe = Recipe::factory()->for($owner)->create([
            'title' => 'Vegane Carbonara',
            'instructions' => 'Spaghetti kochen und mit der Sauce gründlich vermengen.',
        ]);
        $recipe->ingredients()->create([
            'name' => 'Räuchertofu',
            'amount' => '150',
            'unit' => 'g',
            'position' => 0,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.recipes.show', ['userId' => $owner->id, 'recipeId' => $recipe->id]))
            ->assertOk()
            ->assertSee('Vegane Carbonara')
            ->assertSee('Räuchertofu')
            ->assertSee('Nur lesen')
            ->assertDontSee('Rezept bearbeiten')
            ->assertDontSee('Rezept löschen');

        $this->get(route('recipes.show', $recipe))->assertNotFound();
        $this->get(route('recipes.edit', $recipe))->assertNotFound();

        $this->assertTrue(Gate::forUser($admin)->allows('inspect', $recipe));
        $this->assertFalse(Gate::forUser($owner)->allows('inspect', $recipe));
    }

    public function test_a_recipe_cannot_be_opened_under_the_wrong_user_profile(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $recipe = Recipe::factory()->for($owner)->create();

        $this->actingAs($admin)
            ->get(route('admin.users.recipes.show', ['userId' => $other->id, 'recipeId' => $recipe->id]))
            ->assertNotFound();
    }

    public function test_admin_recipe_content_is_escaped(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->create(['name' => '<script>owner</script>']);
        $recipe = Recipe::factory()->for($owner)->create([
            'title' => '<script>recipe</script>',
            'instructions' => '<script>instructions</script>',
        ]);
        $recipe->ingredients()->create([
            'name' => '<script>ingredient</script>',
            'position' => 0,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.recipes.show', ['userId' => $owner->id, 'recipeId' => $recipe->id]))
            ->assertOk()
            ->assertSee($owner->name)
            ->assertSee($recipe->title)
            ->assertSee('<script>ingredient</script>')
            ->assertDontSee('<script>recipe</script>', false);
    }

    public function test_the_console_command_promotes_an_existing_user(): void
    {
        $user = User::factory()->create(['email' => 'admin@example.test']);

        $this->artisan('user:make-admin', ['email' => 'ADMIN@EXAMPLE.TEST'])
            ->expectsConfirmation("Soll {$user->name} ({$user->email}) wirklich Admin werden?", 'yes')
            ->expectsOutput("{$user->name} kann sich jetzt als Admin anmelden.")
            ->assertSuccessful();

        $this->assertSame(UserRole::Admin, $user->refresh()->role);
        $this->assertTrue($user->isAdmin());
    }
}
