<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CanchaPro - Portal de Acceso Dual</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Fonts: Outfit & Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            brand: {
              black: '#06090e',
              dark: '#0c121d',
              card: '#131d2e',
              border: '#1f2e46',
              green: '#00ff88',
              cyan: '#00e5ff',
              red: '#ff3b5c',
              purple: '#a855f7'
            }
          },
          fontFamily: {
            outfit: ['Outfit', 'sans-serif'],
            inter: ['Inter', 'sans-serif']
          }
        }
      }
    }
  </script>
  <style>
    body {
      background-color: #06090e;
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="text-gray-100 flex flex-col items-center justify-center min-h-screen p-4 md:p-10 select-none">

  <!-- Main Container -->
  <div class="max-w-4xl w-full space-y-8">

    <!-- Header & Hero Title -->
    <div class="text-center space-y-2">
      <div class="inline-flex items-center space-x-2 bg-brand-card border border-brand-border px-3.5 py-1.5 rounded-full text-xs font-semibold text-gray-300 mb-2">
        <span class="w-2 h-2 rounded-full bg-brand-green animate-pulse"></span>
        <span>Ecosistema CanchaPro · Laravel 11 + PostgreSQL</span>
      </div>
      <h1 class="font-outfit font-black text-4xl md:text-5xl text-white tracking-tight">
        Cancha<span class="text-brand-green">Pro</span> Suite
      </h1>
      <p class="text-sm text-gray-400 max-w-lg mx-auto">
        Plataforma inteligente de gestión deportiva. Selecciona el acceso según tu rol:
      </p>
    </div>

    <!-- DUAL APPS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

      <!-- ========================================================== -->
      <!-- CARD 1: LA APP MÓVIL EN SÍ (JUGADORES / CAPITANES / ÁRBITROS)-->
      <!-- ========================================================== -->
      <div class="bg-gradient-to-b from-brand-card to-brand-dark border-2 border-brand-green/40 hover:border-brand-green rounded-3xl p-6 flex flex-col justify-between space-y-5 transition duration-300 shadow-xl shadow-brand-green/10 hover:scale-[1.02]">
        <div class="space-y-3">
          <div class="flex justify-between items-center">
            <div class="w-12 h-12 rounded-2xl bg-brand-green/10 text-brand-green flex items-center justify-center text-2xl font-black">
              📱
            </div>
            <span class="text-[10px] bg-brand-green text-black font-extrabold uppercase px-2.5 py-1 rounded-full">
              Android Mobile App
            </span>
          </div>

          <div>
            <h2 class="font-outfit font-extrabold text-2xl text-white">App Móvil CanchaPro</h2>
            <span class="text-xs text-brand-green font-semibold">Para Capitanes, Jugadores y Árbitros</span>
          </div>

          <p class="text-xs text-gray-400 leading-relaxed">
            Gestión completa del día a día en el torneo, convocatorias y planillas arbitrales.
          </p>

          <!-- Key Features List -->
          <ul class="space-y-2 text-xs text-gray-300 pt-2 border-t border-brand-border/60">
            <li class="flex items-center space-x-2">
              <i data-lucide="check-circle" class="w-4 h-4 text-brand-green flex-shrink-0"></i>
              <span><strong>Tu Próximo Partido:</strong> Rival, horario, cancha y [✅ Asistiré].</span>
            </li>
            <li class="flex items-center space-x-2">
              <i data-lucide="check-circle" class="w-4 h-4 text-brand-green flex-shrink-0"></i>
              <span><strong>Lista de Buena Fe:</strong> Convocatoria estricta de 11 titulares.</span>
            </li>
            <li class="flex items-center space-x-2">
              <i data-lucide="check-circle" class="w-4 h-4 text-brand-green flex-shrink-0"></i>
              <span><strong>Trinidad:</strong> Ficha médica unificada + Modo Árbitro.</span>
            </li>
          </ul>
        </div>

        <a href="/app" class="w-full bg-brand-green hover:bg-brand-green/90 text-black font-extrabold py-3.5 rounded-2xl text-xs uppercase tracking-wider text-center transition flex items-center justify-center space-x-2 shadow-lg shadow-brand-green/20">
          <span>Abrir App Móvil (Usuario)</span>
          <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
      </div>

      <!-- ========================================================== -->
      <!-- CARD 2: LA APP ADMINISTRATIVA (BACKOFFICE WEB)            -->
      <!-- ========================================================== -->
      <div class="bg-gradient-to-b from-brand-card to-brand-dark border-2 border-brand-purple/40 hover:border-brand-purple rounded-3xl p-6 flex flex-col justify-between space-y-5 transition duration-300 shadow-xl shadow-brand-purple/10 hover:scale-[1.02]">
        <div class="space-y-3">
          <div class="flex justify-between items-center">
            <div class="w-12 h-12 rounded-2xl bg-brand-purple/10 text-brand-purple flex items-center justify-center text-2xl font-black">
              💻
            </div>
            <span class="text-[10px] bg-brand-purple text-white font-extrabold uppercase px-2.5 py-1 rounded-full">
              Backoffice Desktop
            </span>
          </div>

          <div>
            <h2 class="font-outfit font-extrabold text-2xl text-white">Backoffice Super Admin</h2>
            <span class="text-xs text-brand-purple font-semibold">Para Organizadores y Complejos</span>
          </div>

          <p class="text-xs text-gray-400 leading-relaxed">
            Plataforma para crear torneos, configurar canchas/horarios y generar el sorteo del fixture.
          </p>

          <!-- Key Features List -->
          <ul class="space-y-2 text-xs text-gray-300 pt-2 border-t border-brand-border/60">
            <li class="flex items-center space-x-2">
              <i data-lucide="check-circle" class="w-4 h-4 text-brand-purple flex-shrink-0"></i>
              <span><strong>Crear Torneos:</strong> Asignación de N canchas y franjas horarias.</span>
            </li>
            <li class="flex items-center space-x-2">
              <i data-lucide="check-circle" class="w-4 h-4 text-brand-purple flex-shrink-0"></i>
              <span><strong>Sorteo Automático:</strong> Algoritmo Round Robin de partidos.</span>
            </li>
            <li class="flex items-center space-x-2">
              <i data-lucide="check-circle" class="w-4 h-4 text-brand-purple flex-shrink-0"></i>
              <span><strong>Auditoría Médica:</strong> Validación de aptos físicos y DNI.</span>
            </li>
          </ul>
        </div>

        <a href="/admin" class="w-full bg-brand-purple hover:bg-purple-600 text-white font-extrabold py-3.5 rounded-2xl text-xs uppercase tracking-wider text-center transition flex items-center justify-center space-x-2 shadow-lg shadow-brand-purple/20">
          <span>Abrir Panel Super Admin</span>
          <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
      </div>

    </div>

    <!-- API Health Card -->
    <div class="bg-brand-card/60 border border-brand-border rounded-2xl p-4 flex items-center justify-between text-xs text-gray-400">
      <div class="flex items-center space-x-3">
        <span class="w-2.5 h-2.5 rounded-full bg-brand-cyan animate-ping"></span>
        <span>PostgreSQL 16 en Docker & Laravel API en vivo</span>
      </div>
      <a href="/api/torneos" target="_blank" class="text-brand-cyan hover:underline font-mono">
        GET /api/torneos ↗
      </a>
    </div>

    <!-- Footer Note -->
    <div class="text-center text-xs text-gray-500 pt-2">
      <span>CanchaPro © 2026 · UTN.BA Centro de e-Learning</span>
    </div>

  </div>

  <script>
    lucide.createIcons();
  </script>
</body>
</html>
