<?php

namespace Tests\Feature;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_view_the_login_and_registration_pages(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Anmeldung')
            ->assertSee('Registrieren');

        $this->get('/register')
            ->assertOk()
            ->assertSee('Registrierung')
            ->assertSee('Konto erstellen');
    }

    public function test_a_user_can_register(): void
    {
        Livewire::test(Register::class)
            ->set('name', 'Lisa Muster')
            ->set('email', 'LISA@EXAMPLE.DE')
            ->set('password', 'veganizer-passwort')
            ->set('password_confirmation', 'veganizer-passwort')
            ->call('register')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Lisa Muster',
            'email' => 'lisa@example.de',
        ]);
    }

    public function test_registration_validates_user_input(): void
    {
        Livewire::test(Register::class)
            ->set('name', 'L')
            ->set('email', 'keine-email')
            ->set('password', 'kurz')
            ->set('password_confirmation', 'anders')
            ->call('register')
            ->assertHasErrors(['name', 'email', 'password']);

        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_a_user_can_log_in(): void
    {
        $user = User::factory()->create([
            'email' => 'lisa@example.de',
            'password' => 'veganizer-passwort',
        ]);

        Livewire::test(Login::class)
            ->set('email', 'LISA@EXAMPLE.DE')
            ->set('password', 'veganizer-passwort')
            ->call('login')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'lisa@example.de',
            'password' => 'richtiges-passwort',
        ]);

        Livewire::test(Login::class)
            ->set('email', 'lisa@example.de')
            ->set('password', 'falsches-passwort')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_guests_cannot_open_the_dashboard(): void
    {
        $this->get('/dashboard')
            ->assertRedirect(route('login'));
    }

    public function test_an_authenticated_user_can_log_out(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
