<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sede;
use App\Models\Cancha;
use App\Models\Sponsor;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Sede Principal
        $sede = Sede::firstOrCreate(
            ['nombre' => 'Complejo Deportivo Palermo Central'],
            [
                'direccion' => 'Av. del Libertador 4500',
                'ciudad' => 'Palermo, CABA',
                'telefono' => '+54 9 11 4771-8899',
            ]
        );

        // 2. Canchas con fotos reales, descripciones y ubicaciones
        Cancha::firstOrCreate(
            ['nombre' => 'Cancha 1 - La Bombonerita (F11)'],
            [
                'sede_id' => $sede->id,
                'descripcion' => 'Césped sintético Forbex 50mm con caucho premium homologado FIFA Quality. Cuenta con 8 torres de iluminación LED de 600W para partidos nocturnos, bancos de suplentes techados y vestuarios VIP.',
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

        Cancha::firstOrCreate(
            ['nombre' => 'Cancha 2 - Domo Techado Pro (F7)'],
            [
                'sede_id' => $sede->id,
                'descripcion' => 'Cancha 100% techada climatizada para jugar con lluvia o frío extremo. Césped monofilamento importado y red perimetral suspendida.',
                'ubicacion' => 'Nave Techada B - Pasillo Central',
                'foto_url' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=1000&q=80',
                'tipo_formato' => 'F7',
                'superficie' => 'Sintetico',
                'tiene_iluminacion' => true,
                'es_techada' => true,
                'precio_por_hora' => 22000.00,
                'activa' => true,
            ]
        );

        Cancha::firstOrCreate(
            ['nombre' => 'Cancha 3 - Estadio Césped Natural (F11)'],
            [
                'sede_id' => $sede->id,
                'descripcion' => 'Gramilla natural tipo Bermuda híbrida Tifway 419 con corte profesional a 18mm y sistema de drenaje francés subterráneo. Incluye tribuna lateral para 200 personas.',
                'ubicacion' => 'Sector C - Fondo del Predio, junto al quincho',
                'foto_url' => 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?auto=format&fit=crop&w=1000&q=80',
                'tipo_formato' => 'F11',
                'superficie' => 'Cesped Natural',
                'tiene_iluminacion' => true,
                'es_techada' => false,
                'precio_por_hora' => 35000.00,
                'activa' => true,
            ]
        );

        // 3. Sponsors Oficiales (Propagandas y Patrocinios para monetizar la App)
        Sponsor::firstOrCreate(
            ['marca' => 'Gatorade'],
            [
                'titulo' => 'Hidratación Oficial del Torneo CanchaPro',
                'descripcion' => 'Recuperá electrolitos en cada tiempo. Puntos de hidratación gratuita en todas las canchas.',
                'banner_url' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?auto=format&fit=crop&w=800&q=80',
                'link_destino' => 'https://www.gatorade.com',
                'posicion' => 'header',
                'activo' => true,
            ]
        );

        Sponsor::firstOrCreate(
            ['marca' => 'Nike Football'],
            [
                'titulo' => 'Balón Oficial Flight Pro 2026',
                'descripcion' => 'La pelota oficial de todos los partidos del campeonato. Conseguí la indumentaria oficial con 25% OFF.',
                'banner_url' => 'https://images.unsplash.com/photo-1511886929837-354d827aae26?auto=format&fit=crop&w=800&q=80',
                'link_destino' => 'https://www.nike.com',
                'posicion' => 'feed',
                'activo' => true,
            ]
        );

        Sponsor::firstOrCreate(
            ['marca' => 'SportClub'],
            [
                'titulo' => 'Pase Libre para Jugadores Federados',
                'descripcion' => 'Entrená en más de 100 sedes con tu carnet digital de CanchaPro.',
                'banner_url' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=800&q=80',
                'link_destino' => 'https://www.sportclub.com.ar',
                'posicion' => 'footer',
                'activo' => true,
            ]
        );
    }
}
