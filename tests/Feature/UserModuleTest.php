<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_shows_the_users_list()
    {
        factory(User::class)->create([
            'name' => 'Joel',
        ]);

        factory(User::class)->create([
            'name' => 'Ellie',
        ]);

        $this->get('/usuarios')
            ->assertStatus(200)
            ->assertSee('Listado de usuarios')
            ->assertSee('Joel')
            ->assertSee('Ellie');
    }

    /** @test */
    function it_shows_a_default_message_if_the_users_list_is_empty()
    {
        //DB::table('users')->truncate();   // No es necesaria porque las tablas de la BD estan vacias;

        $this->get('/usuarios')
            ->assertStatus(200)
            ->assertSee('No hay usuarios registrados.');
    }

    /** @test */
    function it_displays_the_users_details()
    {
        $user = factory(User::class)->create([
            'name' => 'Pablo Caraballo'
        ]);

        $this->get('/usuarios/'.$user->id)  // usuarios/5
            ->assertStatus(200)
            ->assertSee('Pablo Caraballo');
    }

    /** @test */
    function it_displays_a_404_error_if_the_user_is_not_found()
    {
        $this->get('/usuarios/999')
            ->assertStatus(404)
            ->assertSee('Página no encontrada');
    }

    /** @test */
    function it_loads_the_new_users_page()
    {
        $this->get('/usuarios/nuevo')
            ->assertStatus(200)
            ->assertSee('Crear usuario');
    }

    /** @test */
    function it_creates_a_new_user()
    {
        $this->withoutExceptionHandling();

        $this->post('/usuarios/', [
            'name' => 'Pablo',
            'email' => 'pablo@udo.edu.ve',
            'password' => '123456'
        ])->assertRedirect('usuarios');     // Podria sustituir 'usuarios' por route('users')

        $this->assertCredentials([ //assertDatabaseHas('users', ...
            'name' => 'Pablo',
            'email' => 'pablo@udo.edu.ve',
            'password' => '123456',
        ]);
    }

    /** @test */
    function the_name_is_required()
    {
        //$this->withoutExceptionHandling();

        $this->from('/usuarios/nuevo')  // Definimos el url anterior.
            ->post('/usuarios/', [
                'name' => '',
                'email' => 'pablo@udo.edu.ve',
                'password' => '123456',
        ])->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio']);

        $this->assertEquals(0, User::count());  // Deberia haber 0 filas en users.

//        $this->assertDatabaseMissing('users', [
//            'email' => 'pablo@udo.edu.ve',
//        ]);
    }

    /** @test */
    function the_email_is_required()
    {
        //$this->withoutExceptionHandling();

        $this->from('/usuarios/nuevo')  // Definimos el url anterior.
            ->post('/usuarios/', [
                'name' => 'Pablo Antonio',
                'email' => '',
                'password' => '123456',
        ])->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email']);

        $this->assertEquals(0, User::count());  // Deberia haber 0 filas en users.
    }

    /** @test */
    function the_email_must_be_valid()
    {
        //$this->withoutExceptionHandling();

        $this->from('/usuarios/nuevo')  // Definimos el url anterior.
            ->post('/usuarios/', [
                'name' => 'Pablo Antonio',
                'email' => 'correo-no-valido',
                'password' => '123456',
        ])->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email']);

        $this->assertEquals(0, User::count());  // Deberia haber 0 filas en users.
    }

    /** @test */
    function the_email_must_be_unique()
    {
        //$this->withoutExceptionHandling();

        factory(User::class)->create([
            'email' => 'pacctono@gmail.com'
        ]);

        $this->from('/usuarios/nuevo')  // Definimos el url anterior.
            ->post('/usuarios/', [
                'name' => 'Pablo Antonio',
                'email' => 'pacctono@gmail.com',
                'password' => '123456',
        ])->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email']);

        $this->assertEquals(1, User::count());  // Deberia haber 0 filas en users.
    }

    /** @test */
    function the_password_is_required()
    {
        //$this->withoutExceptionHandling();

        $this->from('/usuarios/nuevo')  // Definimos el url anterior.
            ->post('/usuarios/', [
                'name' => 'Pablo Antonio',
                'email' => 'pacctono@gmail.com',
                'password' => '',
        ])->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['password']);

        $this->assertEquals(0, User::count());  // Deberia haber 0 filas en users.
    }

    /** @test */
    function it_loads_the_edit_users_page()
    {
        $user = factory(User::class)->create();

        $this->get("/usuarios/{$user->id}/editar")
            ->assertStatus(200)
            ->assertViewIs('users.edit')
            ->assertSee('Editar usuario')
            ->assertViewHas('user', function ($viewUser) use ($user) {
                return $viewUser->id === $user->id;
            });
    }

    /** @test */
    function it_updates_a_user()
    {
        //$this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->put("/usuarios/{$user->id}", [
            'name' => 'Pablo',
            'email' => 'pablo@udo.edu.ve',
            'password' => '1234567'
        ])->assertRedirect("/usuarios/{$user->id}");

        $this->assertCredentials([
            'name' => 'Pablo',
            'email' => 'pablo@udo.edu.ve',
            'password' => '1234567',
        ]);
    }

    /** @test */
    function the_name_is_required_when_updating_the_user()
    {
        //$this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->from("/usuarios/{$user->id}/editar")
            ->put("/usuarios/{$user->id}", [
                'name' => '',
                'email' => 'pablo@udo.edu.ve',
                'password' => '123456',
            ])
            ->assertRedirect("/usuarios/{$user->id}/editar")
            ->assertSessionHasErrors(['name']);

        $this->assertDatabaseMissing('users', ['email' => 'pablo@udo.edu.ve']);
    }

    /** @test */
    function the_email_is_required_when_updating_the_user()
    {
        //$this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->from("/usuarios/$user->id/editar")  // Definimos el url anterior.
            ->put("/usuarios/$user->id", [
                'name' => 'Pablo Antonio',
                'email' => '',
                'password' => '123456',
        ])->assertRedirect("/usuarios/$user->id/editar")
            ->assertSessionHasErrors(['email']);

        $this->assertDatabaseMissing('users', ['name' => 'Pablo Antonio']);  // Este nombre no deberia estar.
    }

    /** @test */
    function the_email_must_be_valid_when_updating_the_user()
    {
        //$this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->from("/usuarios/$user->id/editar")  // Definimos el url anterior.
            ->put("/usuarios/$user->id", [
                'name' => 'Pablo Antonio',
                'email' => 'correo-no-valido',
                'password' => '123456',
        ])->assertRedirect("/usuarios/$user->id/editar")
            ->assertSessionHasErrors(['email']);

        $this->assertDatabaseMissing('users', ['name' => 'Pablo Antonio']);  // Este nombre no deberia estar.
    }

    /** @test */
    function the_email_must_be_unique_when_updating_the_user()
    {

        //$this->withoutExceptionHandling();

        factory(User::class)->create([
            'email' => 'pacctono@example.com'
        ]);

        $user = factory(User::class)->create([
            'email' => 'pacctono@gmail.com'
        ]);

        $this->from("/usuarios/$user->id/editar")  // Definimos el url anterior.
            ->put("/usuarios/$user->id", [
                'name' => 'Pablo Antonio',
                'email' => 'pacctono@example.com',
                'password' => '1234567',
        ])->assertRedirect("usuarios/$user->id/editar")
            ->assertSessionHasErrors(['email']);

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => 'pacctono@gmail.com'
        ]);
        }

    /** @test */
    function the_user_email_can_stay_the_same_when_updating_the_user()
    {
        //$this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'email' => 'pacctono@gmail.com'
        ]);

        $this->from("/usuarios/{$user->id}/editar")  // Definimos el url anterior.
            ->put("/usuarios/{$user->id}", [
                'name' => 'Pablo Antonio',
                'email' => 'pacctono@gmail.com',
                'password' => '12345678'
            ])
            ->assertRedirect("usuarios/{$user->id}");

        $this->assertDatabaseHas('users', [
            'name' => 'Pablo Antonio',
            'email' => 'pacctono@gmail.com'
        ]);
    }

    /** @test */
    function the_password_is_optional_when_updating_the_user()
    {
        //$this->withoutExceptionHandling();

        $old_password = 'CLAVE_ANTERIOR';
        $user = factory(User::class)->create([
            'password' => bcrypt($old_password)
        ]);

        $this->from("/usuarios/{$user->id}/editar")  // Definimos el url anterior.
            ->put("/usuarios/{$user->id}", [
                'name' => 'Pablo Antonio',
                'email' => 'pacctono@gmail.com',
                'password' => ''
            ])
            ->assertRedirect("usuarios/{$user->id}");    // (users.show)

        $this->assertCredentials([
            'name' => 'Pablo Antonio',
            'email' => 'pacctono@gmail.com',
            'password' => $old_password // MUY IMPORTANTE
        ]);
    }

    /** @test */
    function it_deletes_a_user()
    {
        //$this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->delete("/usuarios/{$user->id}")
            ->assertRedirect('/usuarios');

        $this->assertDatabaseMissing('users', [ // Prueba que el usuarios no exista en la bd.
            'id' => $user->id
        ]);

        //$this->assertSame(0, User::count());    // Otra forma, prueba que no hayan filas en 'users'.
    }
}