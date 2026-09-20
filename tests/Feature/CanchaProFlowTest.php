<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Persona;
use App\Models\EncuentroCasual;
use App\Models\Torneo;
use App\Models\Cancha;

class CanchaProFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_registro_usuario_e_inicio_sesion_con_email_valido(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'nombre' => 'Mateo',
            'apellido' => 'Gomez',
            'email' => 'mateo.gomez@ejemplo.com',
            'password' => 'password123',
            'dni' => '40123456',
            'grupo_sanguineo' => 'O+',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['user', 'persona', 'token']);

        $this->assertDatabaseHas('users', ['email' => 'mateo.gomez@ejemplo.com']);
        $this->assertDatabaseHas('personas', ['dni' => '40123456']);
    }

    public function test_creacion_y_listado_de_partidito_casual_multi_deporte(): void
    {
        $user = User::factory()->create(['role' => 'jugador']);
        $persona = Persona::create([
            'user_id' => $user->id,
            'nombre' => 'Lucas',
            'apellido' => 'Perez',
            'dni' => '41987654',
        ]);

        $response = $this->actingAs($user)
            ->postJson('/api/encuentros-casuales', [
                'titulo' => 'Desafío Padel Dobles',
                'deporte' => 'PADEL',
                'cancha_nombre' => 'Cancha 2 Padel Pro',
                'ubicacion' => 'Av. Libertador 4500',
                'fecha_hora' => now()->addDays(2)->format('Y-m-d H:i:s'),
                'formato' => 'DOBLES',
                'modalidad' => 'JUGADORES_SUELTOS',
                'precio_total' => 20000,
                'max_jugadores' => 4,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('encuentro.deporte', 'PADEL')
            ->assertJsonPath('encuentro.max_jugadores', 4);

        $listResponse = $this->getJson('/api/encuentros-casuales?deporte=PADEL');
        $listResponse->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_unirse_a_partidito_casual_por_token(): void
    {
        $creator = User::factory()->create();
        $personaCreator = Persona::create([
            'user_id' => $creator->id,
            'nombre' => 'Organizador',
            'apellido' => 'Test',
            'dni' => '11111111',
        ]);

        $encuentro = EncuentroCasual::create([
            'creador_persona_id' => $personaCreator->id,
            'titulo' => 'Partidito F5',
            'deporte' => 'FUTBOL',
            'cancha_nombre' => 'Cancha 1',
            'ubicacion' => 'Palermo',
            'fecha_hora' => now()->addDay(),
            'formato' => 'F5',
            'modalidad' => 'JUGADORES_SUELTOS',
            'max_jugadores' => 10,
            'share_token' => 'testtoken12345678',
        ]);

        $playerUser = User::factory()->create();
        $personaPlayer = Persona::create([
            'user_id' => $playerUser->id,
            'nombre' => 'Jugador2',
            'apellido' => 'Test',
            'dni' => '22222222',
        ]);

        $joinResponse = $this->actingAs($playerUser)
            ->postJson("/api/encuentros-casuales/token/{$encuentro->share_token}/unirse", [
                'equipo_num' => 1,
            ]);

        $joinResponse->assertStatus(200)
            ->assertJsonPath('mensaje', "¡Te has unido exitosamente al partidito 'Partidito F5'!");

        $this->assertDatabaseHas('encuentro_casual_jugadores', [
            'encuentro_casual_id' => $encuentro->id,
            'persona_id' => $personaPlayer->id,
        ]);
    }

    public function test_subida_y_aprobacion_de_apto_medico_por_super_admin(): void
    {
        $playerUser = User::factory()->create(['role' => 'jugador']);
        $persona = Persona::create([
            'user_id' => $playerUser->id,
            'nombre' => 'Carlos',
            'apellido' => 'Sanchez',
            'dni' => '33333333',
        ]);

        $subidaResponse = $this->actingAs($playerUser)
            ->postJson('/api/auth/apto-medico', [
                'obra_social_prepaga' => 'OSDE 310',
                'observaciones_medicas' => 'Apto fisico al dia',
            ]);

        $subidaResponse->assertStatus(200);

        $adminUser = User::factory()->create(['role' => 'super_admin']);

        $aprobacionResponse = $this->actingAs($adminUser)
            ->postJson("/api/admin/personas/{$persona->id}/apto-medico", [
                'aprobado' => true,
            ]);

        $aprobacionResponse->assertStatus(200)
            ->assertJsonPath('persona.ficha_medica.apto_fisico_aprobado', true);
    }

    public function test_expulsar_jugador_de_partidito_por_creador(): void
    {
        $creator = User::factory()->create();
        $personaCreator = Persona::create([
            'user_id' => $creator->id,
            'nombre' => 'Creador',
            'apellido' => 'Test',
            'dni' => '55555555',
        ]);

        $encuentro = EncuentroCasual::create([
            'creador_persona_id' => $personaCreator->id,
            'titulo' => 'Partidito F7 Test',
            'deporte' => 'FUTBOL',
            'cancha_nombre' => 'Cancha 3',
            'ubicacion' => 'Belgrano',
            'fecha_hora' => now()->addDay(),
            'formato' => 'F7',
            'modalidad' => 'JUGADORES_SUELTOS',
            'max_jugadores' => 14,
            'share_token' => 'tokenexpulsar123',
        ]);

        $playerUser = User::factory()->create();
        $personaPlayer = Persona::create([
            'user_id' => $playerUser->id,
            'nombre' => 'Jugador',
            'apellido' => 'Expulsado',
            'dni' => '66666666',
        ]);

        \App\Models\EncuentroCasualJugador::create([
            'encuentro_casual_id' => $encuentro->id,
            'persona_id' => $personaPlayer->id,
            'equipo_num' => 1,
            'asistencia_confirmada' => true,
        ]);

        $kickResponse = $this->actingAs($creator)
            ->deleteJson("/api/encuentros-casuales/{$encuentro->id}/jugadores/{$personaPlayer->id}");

        $kickResponse->assertStatus(200)
            ->assertJsonPath('mensaje', 'Jugador eliminado del partidito.');

        $this->assertDatabaseMissing('encuentro_casual_jugadores', [
            'encuentro_casual_id' => $encuentro->id,
            'persona_id' => $personaPlayer->id,
        ]);
    }
}
