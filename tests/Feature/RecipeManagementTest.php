<?php

namespace Tests\Feature;

use App\Livewire\Recipes\RecipeEditor;
use App\Livewire\Recipes\RecipeIndex;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RecipeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_recipe_pages(): void
    {
        $recipe = Recipe::factory()->create();

        foreach ([
            route('recipes.index'),
            route('recipes.create'),
            route('recipes.show', $recipe),
            route('recipes.edit', $recipe),
        ] as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
    }

    public function test_a_user_only_sees_their_own_recipes(): void
    {
        $user = User::factory()->create();
        $ownRecipe = Recipe::factory()->for($user)->create(['title' => 'Meine Carbonara']);
        $otherRecipe = Recipe::factory()->create(['title' => 'Fremdes Chili']);

        $this->actingAs($user)
            ->get(route('recipes.index'))
            ->assertOk()
            ->assertSee($ownRecipe->title)
            ->assertDontSee($otherRecipe->title);
    }

    public function test_a_user_can_create_a_recipe_with_ordered_ingredients(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $component = Livewire::test(RecipeEditor::class)
            ->set('title', 'Vegane Carbonara')
            ->set('description', 'Cremig, rauchig und rein pflanzlich.')
            ->set('servings', 2)
            ->set('prepMinutes', 10)
            ->set('cookMinutes', 20)
            ->set('ingredients.0.amount', '250')
            ->set('ingredients.0.unit', 'g')
            ->set('ingredients.0.name', 'Spaghetti')
            ->call('addIngredient')
            ->set('ingredients.1.amount', '120')
            ->set('ingredients.1.unit', 'ml')
            ->set('ingredients.1.name', 'Hafercuisine')
            ->set('ingredients.1.notes', 'ungesüßt')
            ->set('instructions', 'Spaghetti kochen und anschließend mit der cremigen Sauce vermengen.')
            ->call('save')
            ->assertHasNoErrors();

        $recipe = Recipe::query()->sole();

        $component->assertRedirect(route('recipes.show', $recipe));
        $this->assertSame($user->id, $recipe->user_id);
        $this->assertSame(2, $recipe->servings);
        $this->assertDatabaseHas('recipe_ingredients', [
            'recipe_id' => $recipe->id,
            'name' => 'Spaghetti',
            'position' => 0,
        ]);
        $this->assertDatabaseHas('recipe_ingredients', [
            'recipe_id' => $recipe->id,
            'name' => 'Hafercuisine',
            'notes' => 'ungesüßt',
            'position' => 1,
        ]);
    }

    public function test_invalid_recipe_data_is_not_persisted(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(RecipeEditor::class)
            ->set('title', '')
            ->set('ingredients.0.name', '')
            ->set('instructions', 'zu kurz')
            ->call('save')
            ->assertHasErrors([
                'title',
                'ingredients.0.name',
                'instructions',
            ]);

        $this->assertDatabaseCount('recipes', 0);
        $this->assertDatabaseCount('recipe_ingredients', 0);
    }

    public function test_optional_recipe_details_may_be_left_empty(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(RecipeEditor::class)
            ->set('title', 'Einfaches Rezept')
            ->set('description', '')
            ->set('servings', '')
            ->set('prepMinutes', '')
            ->set('cookMinutes', '')
            ->set('ingredients.0.name', 'Tomate')
            ->set('instructions', 'Tomate schneiden und anschließend direkt servieren.')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('recipes', [
            'user_id' => $user->id,
            'title' => 'Einfaches Rezept',
            'description' => null,
            'servings' => null,
            'prep_minutes' => null,
            'cook_minutes' => null,
        ]);
    }

    public function test_an_owner_can_view_and_update_a_recipe(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->for($user)->create([
            'title' => 'Alter Titel',
            'instructions' => 'Die ursprüngliche Zubereitung ist lang genug.',
        ]);
        $oldIngredient = $recipe->ingredients()->create([
            'name' => 'Milch',
            'amount' => '200',
            'unit' => 'ml',
            'position' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('recipes.show', $recipe))
            ->assertOk()
            ->assertSee('Alter Titel')
            ->assertSee('Milch');

        Livewire::test(RecipeEditor::class, ['recipe' => $recipe])
            ->assertSet('title', 'Alter Titel')
            ->set('title', 'Neuer veganer Titel')
            ->set('ingredients.0.name', 'Haferdrink')
            ->set('ingredients.0.notes', 'statt Milch')
            ->set('instructions', 'Alle Zutaten gründlich vermengen und anschließend servieren.')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('recipes.show', $recipe));

        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'title' => 'Neuer veganer Titel',
        ]);
        $this->assertDatabaseMissing('recipe_ingredients', ['id' => $oldIngredient->id]);
        $this->assertDatabaseHas('recipe_ingredients', [
            'recipe_id' => $recipe->id,
            'name' => 'Haferdrink',
            'notes' => 'statt Milch',
            'position' => 0,
        ]);
    }

    public function test_a_user_cannot_open_another_users_recipe(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $recipe = Recipe::factory()->for($owner)->create();

        $this->actingAs($stranger)
            ->get(route('recipes.show', $recipe))
            ->assertNotFound();

        $this->get(route('recipes.edit', $recipe))
            ->assertNotFound();
    }

    public function test_a_user_cannot_delete_another_users_recipe(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $recipe = Recipe::factory()->for($owner)->create();

        $this->actingAs($stranger);

        $accessWasDenied = false;

        try {
            Livewire::test(RecipeIndex::class)
                ->call('deleteRecipe', $recipe->id);
        } catch (ModelNotFoundException) {
            $accessWasDenied = true;
        }

        $this->assertTrue($accessWasDenied);
        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'deleted_at' => null,
        ]);
    }

    public function test_an_owner_can_delete_a_recipe_without_leaving_it_in_their_list(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->for($user)->create(['title' => 'Wird gelöscht']);
        $ingredient = $recipe->ingredients()->create([
            'name' => 'Tofu',
            'position' => 0,
        ]);

        $this->actingAs($user);

        Livewire::test(RecipeIndex::class)
            ->call('deleteRecipe', $recipe->id)
            ->assertHasNoErrors();

        $this->assertSoftDeleted($recipe);
        $this->assertDatabaseHas('recipe_ingredients', ['id' => $ingredient->id]);

        $this->get(route('recipes.index'))
            ->assertOk()
            ->assertDontSee('Wird gelöscht');
    }

    public function test_deleting_a_user_cascades_to_recipes_and_ingredients(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->for($user)->create();
        $ingredient = $recipe->ingredients()->create([
            'name' => 'Tofu',
            'position' => 0,
        ]);

        $user->delete();

        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
        $this->assertDatabaseMissing('recipe_ingredients', ['id' => $ingredient->id]);
    }

    public function test_recipe_content_is_escaped_on_the_detail_page(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->for($user)->create([
            'title' => '<script>alert("title")</script>',
            'instructions' => '<script>alert("instructions")</script>',
        ]);
        $recipe->ingredients()->create([
            'name' => '<script>alert("ingredient")</script>',
            'position' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('recipes.show', $recipe))
            ->assertOk()
            ->assertSee('<script>alert("title")</script>')
            ->assertSee('<script>alert("instructions")</script>')
            ->assertSee('<script>alert("ingredient")</script>');
    }

    public function test_dashboard_shows_recipe_count_and_only_three_recent_recipes(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 4) as $index) {
            Recipe::factory()->for($user)->create([
                'title' => "Rezept {$index}",
                'updated_at' => now()->addMinutes($index),
            ]);
        }

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('4 eigene Rezepte')
            ->assertSee('Rezept 4')
            ->assertSee('Rezept 3')
            ->assertSee('Rezept 2')
            ->assertDontSee('Rezept 1');
    }
}
