<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Persona;
use App\Models\FichaMedica;
use App\Models\Sede;
use App\Models\Cancha;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin Demo Account
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@canchapro.com'],
            [
                'id' => (string) Str::uuid(),
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        $adminPersona = Persona::firstOrCreate(
            ['user_id' => $adminUser->id],
            [
                'id' => (string) Str::uuid(),
                'nombre' => 'Super Admin',
                'apellido' => 'CanchaPro',
                'dni' => '11223344',
                'telefono' => '+54 9 11 1111-2222',
            ]
        );

        FichaMedica::firstOrCreate(
            ['persona_id' => $adminPersona->id],
            [
                'id' => (string) Str::uuid(),
                'grupo_sanguineo' => 'O+',
                'apto_fisico_aprobado' => true,
                'fecha_vencimiento' => Carbon::now()->addYear(),
                'contacto_emergencia' => '+54 9 11 9999-8888',
                'obra_social_prepaga' => 'OSDE 310',
            ]
        );

        // 2. Arbitro Demo Account
        $arbitroUser = User::firstOrCreate(
            ['email' => 'arbitro@canchapro.com'],
            [
                'id' => (string) Str::uuid(),
                'password' => Hash::make('password123'),
                'role' => 'arbitro',
                'is_active' => true,
            ]
        );

        $arbitroPersona = Persona::firstOrCreate(
            ['user_id' => $arbitroUser->id],
            [
                'id' => (string) Str::uuid(),
                'nombre' => 'Árbitro Oficial',
                'apellido' => 'FairPlay',
                'dni' => '22334455',
                'telefono' => '+54 9 11 2222-3333',
            ]
        );

        FichaMedica::firstOrCreate(
            ['persona_id' => $arbitroPersona->id],
            [
                'id' => (string) Str::uuid(),
                'grupo_sanguineo' => 'A+',
                'apto_fisico_aprobado' => true,
                'fecha_vencimiento' => Carbon::now()->addYear(),
                'contacto_emergencia' => '+54 9 11 8888-7777',
                'obra_social_prepaga' => 'Swiss Medical',
            ]
        );

        // 3. Jugador Demo Account
        $jugadorUser = User::firstOrCreate(
            ['email' => 'jugador@canchapro.com'],
            [
                'id' => (string) Str::uuid(),
                'password' => Hash::make('password123'),
                'role' => 'jugador',
                'is_active' => true,
            ]
        );

        $jugadorPersona = Persona::firstOrCreate(
            ['user_id' => $jugadorUser->id],
            [
                'id' => (string) Str::uuid(),
                'nombre' => 'Juan Carlos',
                'apellido' => 'Goleador',
                'dni' => '33445566',
                'telefono' => '+54 9 11 3333-4444',
            ]
        );

        FichaMedica::firstOrCreate(
            ['persona_id' => $jugadorPersona->id],
            [
                'id' => (string) Str::uuid(),
                'grupo_sanguineo' => 'B+',
                'apto_fisico_aprobado' => true,
                'fecha_vencimiento' => Carbon::now()->addYear(),
                'contacto_emergencia' => '+54 9 11 7777-6666',
                'obra_social_prepaga' => 'Galeno',
            ]
        );

        // 4. Sede Principal
        $sede = Sede::firstOrCreate(
            ['nombre' => 'Complejo Deportivo Palermo Central'],
            [
                'direccion' => 'Av. del Libertador 4500',
                'ciudad' => 'Palermo, CABA',
                'telefono' => '+54 9 11 4771-8899',
            ]
        );

        // 5. Canchas
        Cancha::firstOrCreate(
            ['nombre' => 'Cancha 1 - La Bombonerita (F11)'],
            [
                'sede_id' => $sede->id,
                'descripcion' => 'Césped sintético Forbex 50mm con caucho premium homologado FIFA Quality.',
                'ubicacion' => 'Sector A - Acceso Principal por Av. del Libertador 4500',
                'foto_url' => 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?auto=format&fit=crop&w=1000&q=80',
                'tipo_formato' => 'F11',
                'superficie' => 'Sintetico',
                'tiene_iluminacion' => true,
                'es_techada' => false,
                'precio_por_hora' => 28000.00,
                'activa' => true,
            ]
        );

        // 6. Sponsors
        Sponsor::firstOrCreate(
            ['marca' => 'Gatorade'],
            [
                'titulo' => 'Hidratación Oficial del Torneo CanchaPro',
                'descripcion' => 'Recuperá electrolitos en cada tiempo.',
                'banner_url' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?auto=format&fit=crop&w=800&q=80',
                'link_destino' => 'https://www.gatorade.com',
                'posicion' => 'header',
                'activo' => true,
            ]
        );
    }
}
