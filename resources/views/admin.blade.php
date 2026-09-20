<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CanchaPro - Panel Super Admin (Torneos, Canchas & Sponsors)</title>
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
              black: '#080c14',
              dark: '#0f1724',
              card: '#162032',
              border: '#22324d',
              green: '#00ff88',
              cyan: '#00e5ff',
              red: '#ff3b5c',
              yellow: '#ffb800',
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
    body { background-color: #06090e; font-family: 'Inter', sans-serif; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
  </style>
</head>
<body class="text-gray-100 min-h-screen flex flex-col">

  <!-- TOPBAR -->
  <header class="bg-brand-dark/90 backdrop-blur-md border-b border-brand-border sticky top-0 z-50 px-6 py-4 flex justify-between items-center shadow-lg">
    <div class="flex items-center space-x-4">
      <a href="/" class="flex items-center space-x-2">
        <span class="w-3 h-3 rounded-full bg-brand-purple animate-pulse"></span>
        <h1 class="font-outfit font-black text-2xl text-white tracking-tight">
          Cancha<span class="text-brand-purple">Pro</span> <span class="text-xs bg-brand-purple/20 text-brand-purple border border-brand-purple/40 px-2 py-0.5 rounded-full uppercase font-bold">Admin Suite</span>
        </h1>
      </a>
      <span class="text-xs text-gray-400 hidden md:inline">| Torneos, Canchas & Sponsors (PostgreSQL Live)</span>
    </div>

    <div class="flex items-center space-x-3">
      <div id="adminUserBadge" class="text-xs bg-brand-card border border-brand-border px-3 py-1.5 rounded-xl text-gray-300">
        👑 Super Admin: <strong id="adminEmailText">admin@canchapro.com</strong>
      </div>
      <button onclick="adminLogout()" class="text-xs bg-brand-red/20 hover:bg-brand-red/30 text-brand-red border border-brand-red/40 px-3 py-1.5 rounded-xl font-bold transition flex items-center space-x-1" title="Cerrar sesión">
        <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
        <span>Cerrar Sesión</span>
      </button>
    </div>
  </header>

  <!-- MODAL DE LOGIN ADMIN (BLOQUEA SI NO HAY SESIÓN) -->
  <div id="adminLoginModal" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-brand-card border border-brand-purple/50 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-6">
      <div class="text-center space-y-2">
        <div class="w-14 h-14 mx-auto rounded-2xl bg-brand-purple/20 text-brand-purple flex items-center justify-center">
          <i data-lucide="shield-check" class="w-8 h-8"></i>
        </div>
        <h2 class="font-outfit font-black text-2xl text-white">Acceso Administrador</h2>
        <p class="text-xs text-gray-400">Ingresa tus credenciales de Super Admin para gestionar torneos y canchas</p>
      </div>

      <div id="adminLoginAlert" class="hidden p-3 rounded-xl text-xs bg-brand-red/20 text-brand-red border border-brand-red"></div>

      <form id="formAdminLogin" class="space-y-4 text-xs">
        <div>
          <label class="block text-gray-300 font-semibold mb-1">Email de Administrador</label>
          <input type="email" id="adminLoginEmail" required value="admin@canchapro.com" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-brand-purple">
        </div>
        <div>
          <label class="block text-gray-300 font-semibold mb-1">Contraseña</label>
          <input type="password" id="adminLoginPass" required value="admin123" placeholder="••••••••" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-brand-purple">
        </div>
        <button type="submit" id="btnAdminLoginSubmit" class="w-full bg-brand-purple hover:bg-purple-600 text-white font-extrabold py-3 rounded-xl uppercase tracking-wider transition shadow-lg shadow-brand-purple/25">
          Ingresar al Panel
        </button>
      </form>
    </div>
  </div>

  <!-- NAVIGATION TABS -->
  <nav class="bg-brand-dark border-b border-brand-border px-6 py-2.5 flex items-center space-x-2 overflow-x-auto no-scrollbar">
    <button onclick="setTab('torneos')" id="navTabTorneos" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 bg-brand-purple text-white shadow-lg shadow-brand-purple/20">
      <i data-lucide="trophy" class="w-4 h-4"></i>
      <span>1. Torneos & Fixtures</span>
    </button>
    <button onclick="setTab('canchas')" id="navTabCanchas" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 text-gray-400 hover:text-white hover:bg-brand-card">
      <i data-lucide="map-pin" class="w-4 h-4"></i>
      <span>2. Canchas del Complejo</span>
    </button>
    <button onclick="setTab('sponsors')" id="navTabSponsors" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 text-gray-400 hover:text-white hover:bg-brand-card">
      <i data-lucide="megaphone" class="w-4 h-4"></i>
      <span>3. Sponsors & Propagandas</span>
    </button>
    <button onclick="setTab('usuarios')" id="navTabUsuarios" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 text-gray-400 hover:text-white hover:bg-brand-card">
      <i data-lucide="users" class="w-4 h-4"></i>
      <span>4. Usuarios & Árbitros</span>
    </button>
  </nav>

  <!-- MAIN CONTAINER -->
  <main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-8 space-y-8">

    <!-- ALERT BANNER -->
    <div id="notificationBanner" class="hidden p-4 rounded-2xl text-xs font-semibold flex items-center justify-between transition-all"></div>

    <!-- METRICS OVERVIEW -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
      <div class="bg-brand-card border border-brand-border rounded-2xl p-5 space-y-1">
        <span class="text-xs text-gray-400 font-medium">Torneos Registrados</span>
        <h3 id="statTorneos" class="font-outfit font-extrabold text-3xl text-brand-purple">-</h3>
      </div>
      <div class="bg-brand-card border border-brand-border rounded-2xl p-5 space-y-1">
        <span class="text-xs text-gray-400 font-medium">Canchas Habilitadas</span>
        <h3 id="statCanchas" class="font-outfit font-extrabold text-3xl text-brand-cyan">-</h3>
      </div>
      <div class="bg-brand-card border border-brand-border rounded-2xl p-5 space-y-1">
        <span class="text-xs text-gray-400 font-medium">Equipos Participando</span>
        <h3 id="statEquipos" class="font-outfit font-extrabold text-3xl text-brand-green">-</h3>
      </div>
      <div class="bg-brand-card border border-brand-border rounded-2xl p-5 space-y-1">
        <span class="text-xs text-gray-400 font-medium">Sponsors / Anuncios</span>
        <h3 id="statSponsors" class="font-outfit font-extrabold text-3xl text-brand-yellow">-</h3>
      </div>
      <div class="bg-brand-card border border-brand-border rounded-2xl p-5 space-y-1">
        <span class="text-xs text-gray-400 font-medium">Árbitros Designados</span>
        <h3 id="statArbitros" class="font-outfit font-extrabold text-3xl text-brand-red">-</h3>
      </div>
    </div>

    <!-- ================================================================ -->
    <!-- TAB 1: TORNEOS & FIXTURES                                        -->
    <!-- ================================================================ -->
    <div id="sectionTorneos" class="grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- LEFT: CREATE TOURNAMENT FORM -->
      <div class="bg-brand-card border border-brand-border rounded-3xl p-6 space-y-5 lg:col-span-1 shadow-xl">
        <div class="border-b border-brand-border/60 pb-3">
          <h2 class="font-outfit font-extrabold text-xl text-white flex items-center space-x-2">
            <i data-lucide="plus-circle" class="w-5 h-5 text-brand-purple"></i>
            <span>Crear Nuevo Torneo</span>
          </h2>
          <p class="text-xs text-gray-400 mt-1">Con foto, ubicación, categoría y descripción completa.</p>
        </div>

        <form id="formCrearTorneo" class="space-y-3.5 text-xs">
          <!-- Nombre -->
          <div>
            <label class="block text-gray-300 font-semibold mb-1">Nombre del Torneo *</label>
            <input type="text" id="torneoNombre" required placeholder="Ej: Torneo Apertura 2026 - Copa Pro"
              class="w-full bg-brand-dark border border-brand-border rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-brand-purple transition">
          </div>

          <!-- Categoría, Deporte & Formato -->
          <div class="grid grid-cols-3 gap-2">
            <div>
              <label class="block text-gray-300 font-semibold mb-1">Deporte *</label>
              <select id="torneoDeporte" class="w-full bg-brand-dark border border-brand-border rounded-xl px-2 py-2 text-white focus:outline-none focus:border-brand-purple">
                <option value="FUTBOL">⚽ Fútbol</option>
                <option value="PADEL">🎾 Padel</option>
                <option value="TENIS">🎾 Tenis</option>
                <option value="BASQUET">🏀 Básquet</option>
                <option value="VOLEY">🏐 Vóley</option>
                <option value="HOCKEY">🏑 Hockey</option>
              </select>
            </div>
            <div>
              <label class="block text-gray-300 font-semibold mb-1">Categoría *</label>
              <input type="text" id="torneoCategoria" required value="Libre A" placeholder="Ej: Senior +35"
                class="w-full bg-brand-dark border border-brand-border rounded-xl px-2 py-2 text-white focus:outline-none focus:border-brand-purple">
            </div>
            <div>
              <label class="block text-gray-300 font-semibold mb-1">Formato *</label>
              <input type="text" id="torneoFormato" required value="F11" placeholder="Ej: F11, Dobles, 3x3"
                class="w-full bg-brand-dark border border-brand-border rounded-xl px-2 py-2 text-white focus:outline-none focus:border-brand-purple">
            </div>
          </div>

          <!-- Ubicación del Torneo con Google Maps Embed Preview -->
          <div>
            <div class="flex justify-between items-center mb-1">
              <label class="block text-gray-300 font-semibold">Ubicación / Predio (Google Maps) *</label>
              <button type="button" onclick="actualizarMapaPreview()" class="text-[10px] text-brand-purple hover:underline flex items-center space-x-1 font-bold">
                <i data-lucide="map-pin" class="w-3 h-3"></i>
                <span>Previsualizar Mapa</span>
              </button>
            </div>
            <input type="text" id="torneoUbicacion" required value="Av. del Libertador 4500, Palermo, CABA"
              placeholder="Escribe la dirección o nombre del predio..."
              onchange="actualizarMapaPreview()" oninput="actualizarMapaPreview()"
              class="w-full bg-brand-dark border border-brand-border rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-brand-purple">
            
            <!-- MAP PREVIEW IFRAME -->
            <div class="mt-2 rounded-xl overflow-hidden border border-brand-border/60 bg-brand-dark h-32">
              <iframe id="googleMapIframe" class="w-full h-full border-0" loading="lazy" allowfullscreen
                src="https://maps.google.com/maps?q=Av.%20del%20Libertador%204500,%20Palermo,%20CABA&t=&z=15&ie=UTF8&iwloc=&output=embed">
              </iframe>
            </div>
          </div>

          <!-- Descripción -->
          <div>
            <label class="block text-gray-300 font-semibold mb-1">Descripción del Torneo</label>
            <textarea id="torneoDescripcion" rows="2" placeholder="Premios, reglamento, arbitraje profesional y trofeos..."
              class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-purple">Campeonato oficial con 16 fechas, ternas de árbitros federados, seguro médico y premios en efectivo para campeón y subcampeón.</textarea>
          </div>

          <!-- Foto / Banner URL -->
          <div>
            <label class="block text-gray-300 font-semibold mb-1">Foto / Flyer del Torneo (URL)</label>
            <input type="url" id="torneoFotoUrl" value="https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=800&q=80"
              class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-purple text-[11px] font-mono">
            <div class="flex items-center space-x-2 mt-1.5 text-[10px] text-gray-400">
              <span>Sugeridas:</span>
              <button type="button" onclick="document.getElementById('torneoFotoUrl').value='https://images.unsplash.com/photo-1508098682722-e99c43a406b2?auto=format&fit=crop&w=800&q=80'" class="hover:text-brand-purple underline">Estadio</button>
              <button type="button" onclick="document.getElementById('torneoFotoUrl').value='https://images.unsplash.com/photo-1529900748604-07564a03e7a6?auto=format&fit=crop&w=800&q=80'" class="hover:text-brand-purple underline">Nocturno</button>
              <button type="button" onclick="document.getElementById('torneoFotoUrl').value='https://images.unsplash.com/photo-1518091043644-c1d4457512c6?auto=format&fit=crop&w=800&q=80'" class="hover:text-brand-purple underline">Césped</button>
            </div>
          </div>

          <!-- Cupo Máximo & Sede -->
          <div class="grid grid-cols-2 gap-2.5">
            <div>
              <label class="block text-gray-300 font-semibold mb-1">Cupo Máximo</label>
              <select id="torneoMaxEquipos" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-purple">
                <option value="2">2 Equipos (Demo Sorteo)</option>
                <option value="4" selected>4 Equipos</option>
                <option value="8">8 Equipos</option>
                <option value="16">16 Equipos</option>
              </select>
            </div>
            <div>
              <label class="block text-gray-300 font-semibold mb-1">Sede Complejo</label>
              <select id="torneoSede" required class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-purple">
                <option value="">Cargando sedes...</option>
              </select>
            </div>
          </div>

          <!-- Canchas Habilitadas -->
          <div>
            <label class="block text-gray-300 font-semibold mb-1">Canchas Habilitadas para este Torneo</label>
            <div id="canchasCheckboxes" class="space-y-1.5 bg-brand-dark/60 border border-brand-border rounded-xl p-2.5 max-h-28 overflow-y-auto">
              <span class="text-gray-500">Cargando canchas...</span>
            </div>
          </div>

          <button type="submit" id="btnSubmitTorneo" class="w-full bg-brand-purple hover:bg-purple-600 text-white font-extrabold py-3 rounded-xl uppercase tracking-wider transition flex items-center justify-center space-x-2 shadow-lg shadow-brand-purple/20">
            <i data-lucide="sparkles" class="w-4 h-4"></i>
            <span>Publicar Torneo en BD</span>
          </button>
        </form>
      </div>

      <!-- RIGHT: LIVE TOURNAMENTS LIST & FIXTURE -->
      <div class="bg-brand-card border border-brand-border rounded-3xl p-6 space-y-6 lg:col-span-2 shadow-xl flex flex-col justify-between">
        <div>
          <div class="flex justify-between items-center border-b border-brand-border/60 pb-3 mb-4">
            <div>
              <h2 class="font-outfit font-extrabold text-xl text-white flex items-center space-x-2">
                <i data-lucide="trophy" class="w-5 h-5 text-brand-green"></i>
                <span>Torneos Activos en PostgreSQL</span>
              </h2>
              <p class="text-xs text-gray-400 mt-0.5">Mostrando descripción, foto, ubicación y cupos en vivo.</p>
            </div>
            <button onclick="cargarTorneos()" class="text-xs bg-brand-dark hover:bg-brand-border px-3 py-1.5 rounded-xl text-gray-300 border border-brand-border flex items-center space-x-1.5 transition">
              <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
              <span>Refrescar</span>
            </button>
          </div>

          <!-- TORNEOS CONTAINER -->
          <div id="torneosListContainer" class="space-y-4">
            <div class="text-center py-10 text-gray-500 text-xs">Cargando torneos...</div>
          </div>
        </div>
      </div>

    </div>

    <!-- ================================================================ -->
    <!-- TAB 2: GESTIÓN DE CANCHAS                                        -->
    <!-- ================================================================ -->
    <div id="sectionCanchas" class="hidden grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- LEFT: FORMULARIO CREAR CANCHA -->
      <div class="bg-brand-card border border-brand-border rounded-3xl p-6 space-y-5 lg:col-span-1 shadow-xl">
        <div class="border-b border-brand-border/60 pb-3">
          <h2 class="font-outfit font-extrabold text-xl text-white flex items-center space-x-2">
            <i data-lucide="map-pin" class="w-5 h-5 text-brand-cyan"></i>
            <span>Crear Nueva Cancha</span>
          </h2>
          <p class="text-xs text-gray-400 mt-1">Carga canchas con fotos, ubicación y descripción técnica.</p>
        </div>

        <form id="formCrearCancha" class="space-y-3.5 text-xs">
          <!-- Nombre de la cancha -->
          <div>
            <label class="block text-gray-300 font-semibold mb-1">Nombre de la Cancha *</label>
            <input type="text" id="canchaNombre" required placeholder="Ej: Cancha 4 - Estadio Monumentalito"
              class="w-full bg-brand-dark border border-brand-border rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-brand-cyan">
          </div>

          <!-- Formato y Superficie -->
          <div class="grid grid-cols-2 gap-2.5">
            <div>
              <label class="block text-gray-300 font-semibold mb-1">Formato *</label>
              <select id="canchaFormato" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-cyan">
                <option value="F11">Fútbol 11</option>
                <option value="F7">Fútbol 7</option>
                <option value="F8">Fútbol 8</option>
                <option value="F5">Fútbol 5</option>
              </select>
            </div>
            <div>
              <label class="block text-gray-300 font-semibold mb-1">Superficie *</label>
              <select id="canchaSuperficie" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-cyan">
                <option value="Sintetico">Césped Sintético</option>
                <option value="Cesped Natural">Césped Natural</option>
                <option value="Parquet">Parquet / Piso Flotante</option>
                <option value="Cemento">Cemento / Hormigón</option>
              </select>
            </div>
          </div>

          <!-- Ubicación de la cancha -->
          <div>
            <div class="flex justify-between items-center mb-1">
              <label class="block text-gray-300 font-semibold">Ubicación / Dirección *</label>
              <span class="text-[10px] text-brand-cyan font-bold flex items-center space-x-1">
                <i data-lucide="map-pin" class="w-3 h-3"></i>
                <span>Google Maps Preview</span>
              </span>
            </div>
            <input type="text" id="canchaUbicacion" required placeholder="Ej: Sector Norte, ingreso por portón 3, Av. Libertador 4500, CABA"
              oninput="actualizarMapaCanchaAdminPreview()" onchange="actualizarMapaCanchaAdminPreview()"
              class="w-full bg-brand-dark border border-brand-border rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-brand-cyan">
            
            <div class="mt-2 rounded-xl overflow-hidden border border-brand-border/60 bg-brand-dark h-32">
              <iframe id="canchaMapIframeAdmin" class="w-full h-full border-0" loading="lazy" allowfullscreen
                src="https://maps.google.com/maps?q=Av.%20Libertador%204500,%20Palermo,%20CABA&t=&z=15&ie=UTF8&iwloc=&output=embed">
              </iframe>
            </div>
          </div>

          <!-- Descripción -->
          <div>
            <label class="block text-gray-300 font-semibold mb-1">Descripción Técnica & Equipamiento</label>
            <textarea id="canchaDescripcion" rows="2" placeholder="Detalles de iluminación, césped, vestuarios, gradas..."
              class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-cyan">Césped FIFA Quality con caucho volcánico, iluminación LED nocturna de 800W, bancos de suplentes acolchados y cabina de transmisión.</textarea>
          </div>

          <!-- Foto URL -->
          <div>
            <label class="block text-gray-300 font-semibold mb-1">Foto de la Cancha (URL)</label>
            <input type="url" id="canchaFotoUrl" value="https://images.unsplash.com/photo-1529900748604-07564a03e7a6?auto=format&fit=crop&w=800&q=80"
              class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-cyan text-[11px] font-mono">
            <div class="flex items-center space-x-2 mt-1.5 text-[10px] text-gray-400">
              <span>Sugeridas:</span>
              <button type="button" onclick="document.getElementById('canchaFotoUrl').value='https://images.unsplash.com/photo-1529900748604-07564a03e7a6?auto=format&fit=crop&w=800&q=80'" class="hover:text-brand-cyan underline">Sintético Nocturno</button>
              <button type="button" onclick="document.getElementById('canchaFotoUrl').value='https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=800&q=80'" class="hover:text-brand-cyan underline">Techada</button>
              <button type="button" onclick="document.getElementById('canchaFotoUrl').value='https://images.unsplash.com/photo-1508098682722-e99c43a406b2?auto=format&fit=crop&w=800&q=80'" class="hover:text-brand-cyan underline">Natural</button>
            </div>
          </div>

          <!-- Precio por hora & Características -->
          <div class="grid grid-cols-2 gap-2.5">
            <div>
              <label class="block text-gray-300 font-semibold mb-1">Precio x Hora ($)</label>
              <input type="number" id="canchaPrecio" value="25000" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white">
            </div>
            <div class="flex flex-col justify-end space-y-1">
              <label class="flex items-center space-x-1.5 text-gray-300 cursor-pointer">
                <input type="checkbox" id="canchaIluminacion" checked class="rounded bg-brand-dark text-brand-cyan">
                <span>Iluminación LED</span>
              </label>
              <label class="flex items-center space-x-1.5 text-gray-300 cursor-pointer">
                <input type="checkbox" id="canchaTechada" class="rounded bg-brand-dark text-brand-cyan">
                <span>Cancha Techada</span>
              </label>
            </div>
          </div>

          <button type="submit" id="btnSubmitCancha" class="w-full bg-brand-cyan hover:bg-cyan-400 text-black font-extrabold py-3 rounded-xl uppercase tracking-wider transition flex items-center justify-center space-x-2 shadow-lg shadow-brand-cyan/20">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Guardar Cancha en BD</span>
          </button>
        </form>
      </div>

      <!-- RIGHT: LISTADO VISUAL DE CANCHAS -->
      <div class="bg-brand-card border border-brand-border rounded-3xl p-6 space-y-6 lg:col-span-2 shadow-xl">
        <div class="flex justify-between items-center border-b border-brand-border/60 pb-3">
          <div>
            <h2 class="font-outfit font-extrabold text-xl text-white flex items-center space-x-2">
              <i data-lucide="layout-grid" class="w-5 h-5 text-brand-cyan"></i>
              <span>Canchas Registradas en el Complejo</span>
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Fotos, ubicación, tipo de césped y valor horario.</p>
          </div>
          <button onclick="cargarCanchasGrid()" class="text-xs bg-brand-dark hover:bg-brand-border px-3 py-1.5 rounded-xl text-gray-300 border border-brand-border flex items-center space-x-1.5 transition">
            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
            <span>Refrescar</span>
          </button>
        </div>

        <div id="canchasGridContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="text-gray-500 text-xs">Cargando canchas...</div>
        </div>
      </div>

    </div>

    <!-- ================================================================ -->
    <!-- TAB 3: SPONSORS & PUBLICIDAD (MONETIZACIÓN APP STORE)            -->
    <!-- ================================================================ -->
    <div id="sectionSponsors" class="hidden grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- LEFT: FORMULARIO ALTA DE SPONSOR -->
      <div class="bg-brand-card border border-brand-border rounded-3xl p-6 space-y-5 lg:col-span-1 shadow-xl">
        <div class="border-b border-brand-border/60 pb-3">
          <h2 class="font-outfit font-extrabold text-xl text-white flex items-center space-x-2">
            <i data-lucide="megaphone" class="w-5 h-5 text-brand-yellow"></i>
            <span>Añadir Sponsor / Publicidad</span>
          </h2>
          <p class="text-xs text-gray-400 mt-1">Configura banners para financiar y publicar en App Store / Play Store.</p>
        </div>

        <form id="formCrearSponsor" class="space-y-3.5 text-xs">
          <div>
            <label class="block text-gray-300 font-semibold mb-1">Marca / Anunciante *</label>
            <input type="text" id="sponsorMarca" required placeholder="Ej: Gatorade, Nike, Cerveza Quilmes"
              class="w-full bg-brand-dark border border-brand-border rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-brand-yellow">
          </div>

          <div>
            <label class="block text-gray-300 font-semibold mb-1">Título del Anuncio *</label>
            <input type="text" id="sponsorTitulo" required placeholder="Ej: 20% OFF en Botines de Fútbol para Jugadores"
              class="w-full bg-brand-dark border border-brand-border rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-brand-yellow">
          </div>

          <div>
            <label class="block text-gray-300 font-semibold mb-1">Descripción / Promoción</label>
            <textarea id="sponsorDescripcion" rows="2" placeholder="Presentando tu carnet de CanchaPro en sucursales adheridas..."
              class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-yellow"></textarea>
          </div>

          <div>
            <label class="block text-gray-300 font-semibold mb-1">Banner Publicitario (URL de Imagen)</label>
            <input type="url" id="sponsorBannerUrl" value="https://images.unsplash.com/photo-1517838277536-f5f99be501cd?auto=format&fit=crop&w=800&q=80"
              class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-yellow text-[11px] font-mono">
          </div>

          <div>
            <label class="block text-gray-300 font-semibold mb-1">Link de Destino / Web del Sponsor</label>
            <input type="url" id="sponsorLink" placeholder="https://marca.com"
              class="w-full bg-brand-dark border border-brand-border rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-brand-yellow">
          </div>

          <div>
            <label class="block text-gray-300 font-semibold mb-1">Ubicación en la App</label>
            <select id="sponsorPosicion" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-yellow">
              <option value="header">Header Principal (Más visible)</option>
              <option value="feed" selected>Feed / Entre Partidos</option>
              <option value="footer">Footer Inferior</option>
            </select>
          </div>

          <button type="submit" id="btnSubmitSponsor" class="w-full bg-brand-yellow hover:bg-yellow-400 text-black font-extrabold py-3 rounded-xl uppercase tracking-wider transition flex items-center justify-center space-x-2 shadow-lg shadow-brand-yellow/20">
            <i data-lucide="sparkles" class="w-4 h-4"></i>
            <span>Publicar Sponsor en App</span>
          </button>
        </form>
      </div>

      <!-- RIGHT: LISTADO DE SPONSORS -->
      <div class="bg-brand-card border border-brand-border rounded-3xl p-6 space-y-6 lg:col-span-2 shadow-xl">
        <div class="flex justify-between items-center border-b border-brand-border/60 pb-3">
          <div>
            <h2 class="font-outfit font-extrabold text-xl text-white flex items-center space-x-2">
              <i data-lucide="badge-dollar-sign" class="w-5 h-5 text-brand-yellow"></i>
              <span>Patrocinadores & Monetización Activa</span>
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Banners que aparecen automáticamente en la app móvil.</p>
          </div>
          <button onclick="cargarSponsorsList()" class="text-xs bg-brand-dark hover:bg-brand-border px-3 py-1.5 rounded-xl text-gray-300 border border-brand-border flex items-center space-x-1.5 transition">
            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
            <span>Refrescar</span>
          </button>
        </div>

        <div id="sponsorsListContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="text-gray-500 text-xs">Cargando sponsors...</div>
        </div>
      </div>

    </div>

    <!-- ================================================================ -->
    <!-- TAB 4: USUARIOS & DESIGNACIÓN DE ÁRBITROS                        -->
    <!-- ================================================================ -->
    <div id="sectionUsuarios" class="hidden space-y-8">

      <!-- TOP: LISTADO DE USUARIOS Y ASIGNACIÓN DE ROL ÁRBITRO -->
      <div class="bg-brand-card border border-brand-border rounded-3xl p-6 space-y-6 shadow-xl">
        <div class="flex flex-wrap justify-between items-center border-b border-brand-border/60 pb-3 gap-3">
          <div>
            <h2 class="font-outfit font-extrabold text-xl text-white flex items-center space-x-2">
              <i data-lucide="users" class="w-5 h-5 text-brand-red"></i>
              <span>Gestión de Usuarios & Asignación de Rol Árbitro</span>
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Asigna el rol de árbitro a cualquier usuario para que pueda acceder al módulo arbitral y cerrar actas de partido.</p>
          </div>
          <button onclick="cargarUsuariosAdmin()" class="text-xs bg-brand-dark hover:bg-brand-border px-3 py-1.5 rounded-xl text-gray-300 border border-brand-border flex items-center space-x-1.5 transition">
            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
            <span>Actualizar Usuarios</span>
          </button>
        </div>

        <!-- TABLA DE USUARIOS -->
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-xs">
            <thead>
              <tr class="border-b border-brand-border/60 text-gray-400 uppercase text-[10px]">
                <th class="py-3 px-3">Persona / Nombre</th>
                <th class="py-3 px-3">DNI</th>
                <th class="py-3 px-3">Email</th>
                <th class="py-3 px-3">Rol Actual</th>
                <th class="py-3 px-3">Apto Médico</th>
                <th class="py-3 px-3 text-right">Acción Rol</th>
              </tr>
            </thead>
            <tbody id="usuariosTableBody" class="divide-y divide-brand-border/30">
              <tr><td colspan="6" class="py-4 text-center text-gray-500">Cargando usuarios...</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- BOTTOM: ASIGNACIÓN DE ÁRBITROS A PARTIDOS PROGRAMADOS -->
      <div class="bg-brand-card border border-brand-border rounded-3xl p-6 space-y-6 shadow-xl">
        <div class="flex flex-wrap justify-between items-center border-b border-brand-border/60 pb-3 gap-3">
          <div>
            <h2 class="font-outfit font-extrabold text-xl text-white flex items-center space-x-2">
              <i data-lucide="calendar-check" class="w-5 h-5 text-brand-green"></i>
              <span>Designación Arbitral de Partidos Programados</span>
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Asigna el árbitro oficial que dirigirá y cargará el acta de cada encuentro.</p>
          </div>
          <button onclick="cargarPartidosProgramadosAdmin()" class="text-xs bg-brand-dark hover:bg-brand-border px-3 py-1.5 rounded-xl text-gray-300 border border-brand-border flex items-center space-x-1.5 transition">
            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
            <span>Actualizar Partidos</span>
          </button>
        </div>

        <div id="partidosDesignacionContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div class="text-gray-500 text-xs col-span-full text-center py-6">Cargando partidos programados...</div>
        </div>
      </div>

    </div>

  </main>

  <script>
    let ADMIN_TOKEN = localStorage.getItem('canchapro_admin_token') || null;
    let currentAdminUser = JSON.parse(localStorage.getItem('canchapro_admin_user') || 'null');

    function checkAdminAuth() {
      const modal = document.getElementById('adminLoginModal');
      const emailText = document.getElementById('adminEmailText');
      if (!ADMIN_TOKEN || !currentAdminUser) {
        modal.classList.remove('hidden');
        return false;
      } else {
        modal.classList.add('hidden');
        if (emailText) emailText.innerText = currentAdminUser.user?.email || currentAdminUser.email || 'admin@canchapro.com';
        return true;
      }
    }

    function adminLogout() {
      localStorage.removeItem('canchapro_admin_token');
      localStorage.removeItem('canchapro_admin_user');
      ADMIN_TOKEN = null;
      currentAdminUser = null;
      checkAdminAuth();
      notify('Sesión de Administrador cerrada correctamente.', 'success');
    }

    document.getElementById('formAdminLogin').addEventListener('submit', async (e) => {
      e.preventDefault();
      const email = document.getElementById('adminLoginEmail').value;
      const pass = document.getElementById('adminLoginPass').value;
      const btn = document.getElementById('btnAdminLoginSubmit');
      const alertBox = document.getElementById('adminLoginAlert');

      btn.disabled = true;
      btn.innerText = 'Verificando...';
      alertBox.classList.add('hidden');

      try {
        const res = await fetch('/api/auth/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email, password: pass })
        });
        const data = await res.json();

        if (res.ok && data.token) {
          if (data.user?.role !== 'super_admin' && data.user?.role !== 'organizador') {
            alertBox.innerText = 'Acceso denegado. Este usuario no tiene rol de Super Admin.';
            alertBox.classList.remove('hidden');
            btn.disabled = false;
            btn.innerText = 'Ingresar al Panel';
            return;
          }

          ADMIN_TOKEN = data.token;
          currentAdminUser = data;
          localStorage.setItem('canchapro_admin_token', ADMIN_TOKEN);
          localStorage.setItem('canchapro_admin_user', JSON.stringify(data));

          checkAdminAuth();
          notify('¡Bienvenido al Panel Super Admin!');
          cargarSedes();
          cargarTorneos();
          cargarCanchasGrid();
          cargarSponsorsList();
        } else {
          alertBox.innerText = data.error || data.message || 'Credenciales de administrador incorrectas';
          alertBox.classList.remove('hidden');
        }
      } catch (err) {
        alertBox.innerText = 'Error de conexión con el servidor.';
        alertBox.classList.remove('hidden');
      } finally {
        btn.disabled = false;
        btn.innerText = 'Ingresar al Panel';
      }
    });

    function notify(msg, type = 'success') {
      const b = document.getElementById('notificationBanner');
      b.className = `p-4 rounded-2xl text-xs font-semibold flex items-center justify-between transition-all ${
        type === 'success' ? 'bg-brand-green/20 border border-brand-green text-brand-green' : 'bg-brand-red/20 border border-brand-red text-brand-red'
      }`;
      b.innerHTML = `<span>${msg}</span><button onclick="this.parentElement.classList.add('hidden')">✕</button>`;
      b.classList.remove('hidden');
      setTimeout(() => b.classList.add('hidden'), 6000);
    }

    function setTab(tab) {
      document.getElementById('sectionTorneos').classList.add('hidden');
      document.getElementById('sectionCanchas').classList.add('hidden');
      document.getElementById('sectionSponsors').classList.add('hidden');
      document.getElementById('sectionUsuarios').classList.add('hidden');

      document.getElementById('navTabTorneos').className = 'px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 text-gray-400 hover:text-white hover:bg-brand-card';
      document.getElementById('navTabCanchas').className = 'px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 text-gray-400 hover:text-white hover:bg-brand-card';
      document.getElementById('navTabSponsors').className = 'px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 text-gray-400 hover:text-white hover:bg-brand-card';
      document.getElementById('navTabUsuarios').className = 'px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 text-gray-400 hover:text-white hover:bg-brand-card';

      if (tab === 'torneos') {
        document.getElementById('sectionTorneos').classList.remove('hidden');
        document.getElementById('navTabTorneos').className = 'px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 bg-brand-purple text-white shadow-lg shadow-brand-purple/20';
        cargarTorneos();
      } else if (tab === 'canchas') {
        document.getElementById('sectionCanchas').classList.remove('hidden');
        document.getElementById('navTabCanchas').className = 'px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 bg-brand-cyan text-black shadow-lg shadow-brand-cyan/20';
        cargarCanchasGrid();
      } else if (tab === 'sponsors') {
        document.getElementById('sectionSponsors').classList.remove('hidden');
        document.getElementById('navTabSponsors').className = 'px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 bg-brand-yellow text-black shadow-lg shadow-brand-yellow/20';
        cargarSponsorsList();
      } else if (tab === 'usuarios') {
        document.getElementById('sectionUsuarios').classList.remove('hidden');
        document.getElementById('navTabUsuarios').className = 'px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 bg-brand-red text-white shadow-lg shadow-brand-red/20';
        cargarUsuariosAdmin();
        cargarPartidosProgramadosAdmin();
      }
      lucide.createIcons();
    }

    // 1. CARGAR SEDES Y CANCHAS
    let sedesData = [];
    async function cargarSedes() {
      try {
        const res = await fetch('/api/sedes');
        sedesData = await res.json();
        
        const sel = document.getElementById('torneoSede');
        sel.innerHTML = '';
        const chkContainer = document.getElementById('canchasCheckboxes');
        chkContainer.innerHTML = '';

        let totalCanchas = 0;
        sedesData.forEach(s => {
          sel.innerHTML += `<option value="${s.id}">${s.nombre} (${s.ciudad})</option>`;
          if (s.canchas && s.canchas.length > 0) {
            totalCanchas += s.canchas.length;
            s.canchas.forEach(c => {
              chkContainer.innerHTML += `
                <label class="flex items-center space-x-2 text-gray-300 hover:text-white cursor-pointer">
                  <input type="checkbox" name="canchas" value="${c.id}" checked class="rounded bg-brand-dark border-brand-border text-brand-purple focus:ring-0">
                  <span>${c.nombre} <span class="text-[10px] text-brand-cyan">(${c.tipo_formato})</span></span>
                </label>
              `;
            });
          }
        });
        document.getElementById('statCanchas').innerText = totalCanchas;
      } catch (err) {
        console.error('Error cargando sedes:', err);
      }
    }

    function actualizarMapaPreview() {
      const ubicacion = document.getElementById('torneoUbicacion').value;
      const iframe = document.getElementById('googleMapIframe');
      if (ubicacion && iframe) {
        iframe.src = `https://maps.google.com/maps?q=${encodeURIComponent(ubicacion)}&t=&z=15&ie=UTF8&iwloc=&output=embed`;
      }
    }

    // 2. CARGAR TORNEOS
    async function cargarTorneos() {
      try {
        const res = await fetch('/api/torneos');
        const raw = await res.json();
        const torneos = Array.isArray(raw) ? raw : (raw.data?.data || raw.data || []);
        
        document.getElementById('statTorneos').innerText = torneos.length;
        let totalEquipos = 0;
        const container = document.getElementById('torneosListContainer');

        if (torneos.length === 0) {
          container.innerHTML = '<div class="text-center py-10 text-gray-500 text-xs">No hay torneos creados aún. ¡Crea el primero en el formulario de la izquierda!</div>';
          return;
        }

        container.innerHTML = '';
        torneos.forEach(t => {
          const numEquipos = t.equipos ? t.equipos.length : 0;
          totalEquipos += numEquipos;
          const canchasNombres = t.canchas ? t.canchas.map(c => c.nombre).join(', ') : 'Ninguna';
          const foto = t.foto_url || 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?auto=format&fit=crop&w=800&q=80';
          const ubicacion = t.ubicacion || (t.sede ? `${t.sede.nombre}, ${t.sede.direccion}` : 'Sede Central');
          const descripcion = t.descripcion || 'Torneo oficial de fútbol amateur con fixtures automáticos y arbitraje digital.';

          container.innerHTML += `
            <div class="bg-brand-dark/90 border border-brand-border hover:border-brand-purple/50 rounded-3xl overflow-hidden transition shadow-xl flex flex-col md:flex-row">
              <div class="w-full md:w-48 h-40 md:h-auto bg-cover bg-center flex-shrink-0 relative" style="background-image: url('${foto}');">
                <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-black/80 via-black/30 to-transparent"></div>
                <div class="absolute top-3 left-3">
                  <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-brand-purple text-white shadow">
                    ${t.categoria}
                  </span>
                </div>
              </div>

              <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                <div>
                  <div class="flex justify-between items-start">
                    <h3 class="font-outfit font-extrabold text-lg text-white">${t.nombre}</h3>
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full ${
                      t.estado === 'EN_JUEGO' ? 'bg-brand-green/20 text-brand-green border border-brand-green/40' : 'bg-brand-cyan/20 text-brand-cyan border border-brand-cyan/40'
                    }">${t.estado}</span>
                  </div>
                  
                  <div class="text-[11px] text-gray-400 mt-1 flex items-center space-x-1.5">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-brand-cyan flex-shrink-0"></i>
                    <span>${ubicacion}</span>
                  </div>

                  <p class="text-xs text-gray-300 mt-2 leading-relaxed line-clamp-2">
                    ${descripcion}
                  </p>
                </div>

                <div class="pt-3 border-t border-brand-border/40 flex flex-wrap justify-between items-center gap-3">
                  <div class="text-xs text-gray-400 flex items-center space-x-3">
                    <span>⚽ <strong>${t.formato_juego}</strong></span>
                    <span>🏟️ <strong>Canchas:</strong> ${t.canchas ? t.canchas.length : 0}</span>
                    <span class="text-brand-green font-bold bg-brand-card px-2 py-0.5 rounded-lg border border-brand-border">
                      ${numEquipos} / ${t.max_equipos} Equipos
                    </span>
                  </div>

                  <div class="flex items-center space-x-2">
                    ${t.estado === 'EN_JUEGO'
                      ? `<span class="text-xs font-bold text-emerald-400 bg-emerald-950/40 border border-emerald-500/40 px-3 py-1.5 rounded-xl flex items-center space-x-1.5">
                          <i data-lucide="check-check" class="w-4 h-4"></i>
                          <span>Fixture en Juego</span>
                         </span>`
                      : `<button onclick="sortearFixture('${t.id}', '${t.nombre.replace(/'/g, "\\'")}')" class="text-xs bg-gradient-to-r from-brand-purple to-purple-600 hover:from-purple-600 hover:to-brand-purple text-white font-extrabold px-3.5 py-1.5 rounded-xl transition shadow-lg shadow-brand-purple/20 flex items-center space-x-1.5">
                          <i data-lucide="shuffle" class="w-3.5 h-3.5"></i>
                          <span>Sortear Fixture</span>
                         </button>`
                    }
                    ${numEquipos === 0
                      ? `<button onclick="eliminarTorneo('${t.id}', '${t.nombre.replace(/'/g, "\\'")}')" class="text-xs text-brand-red hover:text-white bg-brand-red/10 hover:bg-brand-red border border-brand-red/30 px-2.5 py-1.5 rounded-xl font-bold transition flex items-center space-x-1" title="Eliminar Torneo sin equipos">
                          <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                          <span>Eliminar</span>
                         </button>`
                      : `<span title="No se puede eliminar: tiene ${numEquipos} equipo(s) inscripto(s)" class="text-[10px] text-gray-500 bg-brand-dark px-2 py-1 rounded-lg border border-brand-border cursor-not-allowed">
                          🔒 Inscripto (${numEquipos})
                         </span>`
                    }
                  </div>
                </div>
              </div>
            </div>
          `;
        });

        document.getElementById('statEquipos').innerText = totalEquipos;
        lucide.createIcons();
      } catch (err) {
        console.error('Error cargando torneos:', err);
      }
    }

    // ELIMINAR TORNEO
    async function eliminarTorneo(id, nombre) {
      if (!confirm(`¿Estás seguro de que deseas eliminar el torneo "${nombre}"?`)) return;

      try {
        const res = await fetch(`/api/admin/torneos/${id}`, {
          method: 'DELETE',
          headers: {
            'Authorization': `Bearer ${ADMIN_TOKEN}`
          }
        });
        const data = await res.json();
        if (res.ok) {
          notify(`🗑️ ${data.mensaje || 'Torneo eliminado correctamente'}`);
          cargarTorneos();
        } else {
          notify(`❌ ${data.error || data.message || 'Error al eliminar el torneo'}`, 'error');
        }
      } catch (err) {
        notify('Error de comunicación con el servidor', 'error');
      }
    }

    // 3. CARGAR CANCHAS GRID
    async function cargarCanchasGrid() {
      try {
        const res = await fetch('/api/canchas');
        const canchas = await res.json();
        const container = document.getElementById('canchasGridContainer');
        document.getElementById('statCanchas').innerText = canchas.length;

        if (canchas.length === 0) {
          container.innerHTML = '<div class="text-gray-500 text-xs">No hay canchas registradas aún.</div>';
          return;
        }

        container.innerHTML = '';
        canchas.forEach(c => {
          const foto = c.foto_url || 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?auto=format&fit=crop&w=800&q=80';
          container.innerHTML += `
            <div class="bg-brand-dark/90 border border-brand-border rounded-2xl overflow-hidden shadow-lg flex flex-col justify-between">
              <div class="h-36 bg-cover bg-center relative" style="background-image: url('${foto}');">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                <div class="absolute top-2.5 right-2.5 flex space-x-1.5">
                  <span class="text-[10px] bg-brand-cyan text-black font-extrabold px-2 py-0.5 rounded-full uppercase">${c.tipo_formato}</span>
                  <span class="text-[10px] bg-black/60 backdrop-blur-md text-white font-bold px-2 py-0.5 rounded-full">${c.superficie}</span>
                </div>
                <div class="absolute bottom-2 left-3 right-3">
                  <h4 class="font-outfit font-extrabold text-base text-white">${c.nombre}</h4>
                </div>
              </div>

              <div class="p-4 space-y-2.5 text-xs flex-1 flex flex-col justify-between">
                <div class="space-y-1.5">
                  <div class="flex items-start space-x-1 text-gray-300">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-brand-cyan flex-shrink-0 mt-0.5"></i>
                    <a href="https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(c.ubicacion || (c.sede ? c.sede.direccion : 'Av. Libertador 4500'))}" target="_blank" class="text-[11px] text-brand-cyan hover:underline flex items-center space-x-1 truncate">
                      <span class="truncate">${c.ubicacion || (c.sede ? c.sede.direccion : 'Av. Libertador 4500')} 📍</span>
                    </a>
                  </div>
                  <p class="text-gray-400 text-[11px] leading-relaxed line-clamp-2">
                    ${c.descripcion || 'Cancha equipada para partidos oficiales de liga.'}
                  </p>
                </div>

                <div class="pt-2 border-t border-brand-border/40 flex justify-between items-center text-[11px]">
                  <span class="text-brand-green font-bold">$${Number(c.precio_por_hora).toLocaleString('es-AR')} / hora</span>
                  <div class="flex items-center space-x-2">
                    ${c.tiene_iluminacion ? '<span title="Iluminación LED" class="text-gray-400">💡 LED</span>' : ''}
                    ${c.es_techada ? '<span title="Techada" class="text-gray-400">☂️ Techada</span>' : ''}
                    <button onclick="eliminarCancha('${c.id}', '${c.nombre.replace(/'/g, "\\'")}')" class="text-red-400 hover:text-red-300 hover:bg-red-500/10 p-1.5 rounded-lg transition" title="Eliminar Cancha">
                      <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          `;
        });
        lucide.createIcons();
      } catch (err) {
        console.error('Error cargando canchas grid:', err);
      }
    }

    // 4. CARGAR SPONSORS LIST
    async function cargarSponsorsList() {
      try {
        const res = await fetch('/api/sponsors');
        const sponsors = await res.json();
        const container = document.getElementById('sponsorsListContainer');
        document.getElementById('statSponsors').innerText = sponsors.length;

        if (sponsors.length === 0) {
          container.innerHTML = '<div class="text-gray-500 text-xs">No hay sponsors registrados aún.</div>';
          return;
        }

        container.innerHTML = '';
        sponsors.forEach(s => {
          const banner = s.banner_url || 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?auto=format&fit=crop&w=800&q=80';
          container.innerHTML += `
            <div class="bg-brand-dark/90 border border-brand-border rounded-2xl overflow-hidden shadow-lg flex flex-col justify-between">
              <div class="h-32 bg-cover bg-center relative" style="background-image: url('${banner}');">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                <div class="absolute top-2.5 right-2.5">
                  <span class="text-[10px] bg-brand-yellow text-black font-extrabold px-2 py-0.5 rounded-full uppercase">${s.posicion}</span>
                </div>
                <div class="absolute bottom-2 left-3 right-3">
                  <span class="text-[10px] font-bold text-brand-yellow uppercase tracking-wider">${s.marca}</span>
                  <h4 class="font-outfit font-extrabold text-sm text-white">${s.titulo}</h4>
                </div>
              </div>

              <div class="p-4 space-y-2 text-xs flex-1 flex flex-col justify-between">
                <p class="text-gray-400 text-[11px] leading-relaxed">
                  ${s.descripcion || 'Sponsor oficial del campeonato CanchaPro.'}
                </p>
                <div class="pt-2 border-t border-brand-border/40 flex justify-between items-center text-[11px]">
                  <a href="${s.link_destino || '#'}" target="_blank" class="text-brand-cyan hover:underline flex items-center space-x-1">
                    <span>Visitar Web</span>
                    <i data-lucide="external-link" class="w-3 h-3"></i>
                  </a>
                  <div class="flex items-center space-x-2">
                    <span class="text-brand-green font-semibold">● Activo en App</span>
                    <button onclick="eliminarSponsor('${s.id}', '${s.marca.replace(/'/g, "\\'")}')" class="text-red-400 hover:text-red-300 hover:bg-red-500/10 p-1.5 rounded-lg transition" title="Eliminar Propaganda">
                      <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          `;
        });
        lucide.createIcons();
      } catch (err) {
        console.error('Error cargando sponsors:', err);
      }
    }

    // 5. CREAR TORNEO (POST /api/admin/torneos)
    document.getElementById('formCrearTorneo').addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = document.getElementById('btnSubmitTorneo');
      btn.disabled = true;
      btn.innerText = 'Guardando en PostgreSQL...';

      const canchasChecked = Array.from(document.querySelectorAll('input[name="canchas"]:checked')).map(c => c.value);

      const payload = {
        nombre: document.getElementById('torneoNombre').value,
        deporte: document.getElementById('torneoDeporte').value,
        sede_id: document.getElementById('torneoSede').value,
        categoria: document.getElementById('torneoCategoria').value,
        formato_juego: document.getElementById('torneoFormato').value,
        ubicacion: document.getElementById('torneoUbicacion').value,
        descripcion: document.getElementById('torneoDescripcion').value,
        foto_url: document.getElementById('torneoFotoUrl').value,
        max_equipos: parseInt(document.getElementById('torneoMaxEquipos').value),
        canchas_ids: canchasChecked
      };

      try {
        const res = await fetch('/api/admin/torneos', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${ADMIN_TOKEN}`
          },
          body: JSON.stringify(payload)
        });

        const data = await res.json();
        if (res.ok) {
          notify(`🎉 ¡Torneo "${payload.nombre}" (${payload.categoria}) creado con éxito!`);
          document.getElementById('formCrearTorneo').reset();
          cargarSedes();
          cargarTorneos();
        } else {
          notify(`❌ Error: ${data.message || JSON.stringify(data)}`, 'error');
        }
      } catch (err) {
        notify('❌ Error de conexión con la API', 'error');
      } finally {
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="sparkles" class="w-4 h-4"></i><span>Publicar Torneo en BD</span>';
        lucide.createIcons();
      }
    });

    // 6. CREAR CANCHA (POST /api/admin/canchas)
    document.getElementById('formCrearCancha').addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = document.getElementById('btnSubmitCancha');
      btn.disabled = true;
      btn.innerText = 'Guardando Cancha...';

      const payload = {
        nombre: document.getElementById('canchaNombre').value,
        tipo_formato: document.getElementById('canchaFormato').value,
        superficie: document.getElementById('canchaSuperficie').value,
        ubicacion: document.getElementById('canchaUbicacion').value,
        descripcion: document.getElementById('canchaDescripcion').value,
        foto_url: document.getElementById('canchaFotoUrl').value,
        precio_por_hora: parseFloat(document.getElementById('canchaPrecio').value),
        tiene_iluminacion: document.getElementById('canchaIluminacion').checked,
        es_techada: document.getElementById('canchaTechada').checked,
      };

      try {
        const res = await fetch('/api/admin/canchas', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${ADMIN_TOKEN}`
          },
          body: JSON.stringify(payload)
        });

        const data = await res.json();
        if (res.ok) {
          notify(`🏟️ ¡Cancha "${payload.nombre}" creada con éxito en la base de datos!`);
          document.getElementById('formCrearCancha').reset();
          cargarCanchasGrid();
          cargarSedes();
        } else {
          notify(`❌ Error: ${data.message || JSON.stringify(data)}`, 'error');
        }
      } catch (err) {
        notify('❌ Error al registrar cancha', 'error');
      } finally {
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="plus-circle" class="w-4 h-4"></i><span>Guardar Cancha en BD</span>';
        lucide.createIcons();
      }
    });

    function actualizarMapaCanchaAdminPreview() {
      const ubica = document.getElementById('canchaUbicacion').value || 'Av. Libertador 4500, Palermo, CABA';
      const iframe = document.getElementById('canchaMapIframeAdmin');
      if (iframe) {
        iframe.src = `https://maps.google.com/maps?q=${encodeURIComponent(ubica)}&t=&z=15&ie=UTF8&iwloc=&output=embed`;
      }
    }

    // 7. CREAR SPONSOR (POST /api/admin/sponsors)
    document.getElementById('formCrearSponsor').addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = document.getElementById('btnSubmitSponsor');
      btn.disabled = true;
      btn.innerText = 'Guardando Sponsor...';

      const payload = {
        marca: document.getElementById('sponsorMarca').value,
        titulo: document.getElementById('sponsorTitulo').value,
        descripcion: document.getElementById('sponsorDescripcion').value,
        banner_url: document.getElementById('sponsorBannerUrl').value,
        link_destino: document.getElementById('sponsorLink').value,
        posicion: document.getElementById('sponsorPosicion').value,
      };

      try {
        const res = await fetch('/api/admin/sponsors', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${ADMIN_TOKEN}`
          },
          body: JSON.stringify(payload)
        });

        const data = await res.json();
        if (res.ok) {
          notify(`📢 ¡Sponsor "${payload.marca}" activado con éxito!`);
          document.getElementById('formCrearSponsor').reset();
          cargarSponsorsList();
        } else {
          notify(`❌ Error: ${data.message || JSON.stringify(data)}`, 'error');
        }
      } catch (err) {
        notify('❌ Error al registrar sponsor', 'error');
      } finally {
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="sparkles" class="w-4 h-4"></i><span>Publicar Sponsor en App</span>';
        lucide.createIcons();
      }
    });

    // 8. SORTEAR FIXTURE
    async function sortearFixture(torneoId, torneoNombre) {
      if (!confirm(`¿Deseas realizar el sorteo automático del fixture para "${torneoNombre}"?`)) return;

      try {
        const res = await fetch(`/api/admin/torneos/${torneoId}/sortear-fixture`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${ADMIN_TOKEN}`
          }
        });

        const data = await res.json();
        if (res.ok) {
          notify(`🎲 ¡Fixture sorteado! Se generaron ${data.resultado.total_partidos} partidos con horarios y canchas.`);
          cargarTorneos();
        } else {
          notify(`❌ Error: ${data.error || data.message}`, 'error');
        }
      } catch (err) {
        notify('❌ Error ejecutando el sorteo', 'error');
      }
    }

    // 9. ELIMINAR CANCHA (DELETE /api/admin/canchas/{id})
    async function eliminarCancha(canchaId, nombre) {
      if (!confirm(`¿Estás seguro de que deseas eliminar la cancha "${nombre}"?`)) return;

      try {
        const res = await fetch(`/api/admin/canchas/${canchaId}`, {
          method: 'DELETE',
          headers: {
            'Authorization': `Bearer ${ADMIN_TOKEN}`
          }
        });

        const data = await res.json();
        if (res.ok) {
          notify(`🗑️ Cancha "${nombre}" eliminada con éxito.`);
          cargarCanchasGrid();
          cargarSedes();
        } else {
          notify(`❌ Error al eliminar: ${data.message || data.error}`, 'error');
        }
      } catch (err) {
        notify('❌ Error conectando con el servidor', 'error');
      }
    }

    // 10. ELIMINAR SPONSOR (DELETE /api/admin/sponsors/{id})
    async function eliminarSponsor(sponsorId, marca) {
      if (!confirm(`¿Estás seguro de que deseas eliminar la propaganda de "${marca}"?`)) return;

      try {
        const res = await fetch(`/api/admin/sponsors/${sponsorId}`, {
          method: 'DELETE',
          headers: {
            'Authorization': `Bearer ${ADMIN_TOKEN}`
          }
        });

        const data = await res.json();
        if (res.ok) {
          notify(`🗑️ Propaganda de "${marca}" eliminada exitosamente.`);
          cargarSponsorsList();
        } else {
          notify(`❌ Error al eliminar sponsor: ${data.message || data.error}`, 'error');
        }
      } catch (err) {
        notify('❌ Error conectando con el servidor', 'error');
      }
    }

    // 11. GESTIÓN DE USUARIOS Y ÁRBITROS (ADMIN)
    let usuariosAdminData = [];
    async function cargarUsuariosAdmin() {
      if (!ADMIN_TOKEN) return;
      const tbody = document.getElementById('usuariosTableBody');
      tbody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-gray-500">Cargando usuarios...</td></tr>';

      try {
        const res = await fetch('/api/admin/usuarios', {
          headers: { 'Authorization': `Bearer ${ADMIN_TOKEN}` }
        });
        usuariosAdminData = await res.json();

        const arbitros = usuariosAdminData.filter(u => u.role === 'arbitro');
        document.getElementById('statArbitros').innerText = arbitros.length;

        if (usuariosAdminData.length === 0) {
          tbody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-gray-500">No hay usuarios registrados.</td></tr>';
          return;
        }

        tbody.innerHTML = usuariosAdminData.map(u => {
          const p = u.persona || {};
          const nombreCompleto = p.nombre ? `${p.nombre} ${p.apellido}` : (u.email.split('@')[0]);
          const dni = p.dni || '-';
          const estaAprobado = p.ficha_medica ? p.ficha_medica.apto_fisico_aprobado : false;
          
          let aptoBadge = '<span class="text-red-400">Sin Ficha</span>';
          if (p.ficha_medica) {
            aptoBadge = estaAprobado 
              ? '<span class="bg-green-900/40 text-brand-green border border-brand-green/30 px-2 py-0.5 rounded-full font-bold text-[10px]">✅ Aprobado</span>'
              : '<span class="bg-yellow-900/40 text-yellow-400 border border-yellow-500/30 px-2 py-0.5 rounded-full font-bold text-[10px]">⏳ Pendiente</span>';
          }

          let rolBadge = '';
          if (u.role === 'super_admin') {
            rolBadge = '<span class="bg-purple-900/40 text-brand-purple border border-brand-purple/40 px-2.5 py-0.5 rounded-full font-bold text-[10px]">👑 Super Admin</span>';
          } else if (u.role === 'arbitro') {
            rolBadge = '<span class="bg-red-900/40 text-brand-red border border-brand-red/40 px-2.5 py-0.5 rounded-full font-bold text-[10px]">⚖️ Árbitro Oficial</span>';
          } else if (u.role === 'capitan') {
            rolBadge = '<span class="bg-cyan-900/40 text-brand-cyan border border-brand-cyan/40 px-2.5 py-0.5 rounded-full font-bold text-[10px]">⭐ Capitán</span>';
          } else {
            rolBadge = '<span class="bg-green-900/40 text-brand-green border border-brand-green/40 px-2.5 py-0.5 rounded-full font-bold text-[10px]">⚽ Jugador</span>';
          }

          let aptoActions = '';
          if (p.id) {
            if (estaAprobado) {
              aptoActions = `<button onclick="aprobarAptoMedicoAdmin('${p.id}', false)" class="px-2 py-1 rounded-lg bg-brand-red/20 hover:bg-brand-red/30 text-brand-red text-[10px] font-bold border border-brand-red/40 transition">Rechazar Apto</button>`;
            } else {
              aptoActions = `<button onclick="aprobarAptoMedicoAdmin('${p.id}', true)" class="px-2 py-1 rounded-lg bg-brand-green/20 hover:bg-brand-green/30 text-brand-green text-[10px] font-bold border border-brand-green/40 transition">Aprobar Apto</button>`;
            }
          }

          return `
            <tr class="hover:bg-brand-dark/40 transition">
              <td class="py-3 px-3">
                <div class="font-bold text-white">${nombreCompleto}</div>
                <div class="text-[10px] text-gray-500 font-mono">${u.id.substring(0, 8)}...</div>
              </td>
              <td class="py-3 px-3 font-mono text-gray-300">${dni}</td>
              <td class="py-3 px-3 text-gray-300">${u.email}</td>
              <td class="py-3 px-3">${rolBadge}</td>
              <td class="py-3 px-3">
                <div class="flex items-center space-x-1.5">
                  ${aptoBadge}
                  ${aptoActions}
                </div>
              </td>
              <td class="py-3 px-3 text-right">
                <div class="inline-flex items-center space-x-1.5">
                  <select onchange="cambiarRolUsuario('${u.id}', this.value)" class="bg-brand-dark border border-brand-border rounded-xl px-2.5 py-1.5 text-[11px] text-gray-200 focus:outline-none focus:border-brand-purple">
                    <option value="jugador" ${u.role === 'jugador' ? 'selected' : ''}>⚽ Jugador</option>
                    <option value="capitan" ${u.role === 'capitan' ? 'selected' : ''}>⭐ Capitán</option>
                    <option value="arbitro" ${u.role === 'arbitro' ? 'selected' : ''}>⚖️ Árbitro</option>
                    <option value="super_admin" ${u.role === 'super_admin' ? 'selected' : ''}>👑 Super Admin</option>
                  </select>
                </div>
              </td>
            </tr>
          `;
        }).join('');
      } catch (err) {
        tbody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-red-400">Error cargando usuarios.</td></tr>';
      }
    }

    async function aprobarAptoMedicoAdmin(personaId, aprobado) {
      if (!ADMIN_TOKEN) return;
      try {
        const res = await fetch(`/api/admin/personas/${personaId}/apto-medico`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${ADMIN_TOKEN}`
          },
          body: JSON.stringify({ aprobado })
        });
        const data = await res.json();
        if (res.ok) {
          notify(data.mensaje || 'Estado de apto médico actualizado correctamente.');
          cargarUsuariosAdmin();
        } else {
          notify(`❌ Error: ${data.message || data.error}`, 'error');
        }
      } catch (err) {
        notify('❌ Error de comunicación con el servidor', 'error');
      }
    }

    async function cambiarRolUsuario(userId, nuevoRol) {
      if (!ADMIN_TOKEN) return;
      try {
        const res = await fetch(`/api/admin/usuarios/${userId}/rol`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${ADMIN_TOKEN}`
          },
          body: JSON.stringify({ role: nuevoRol })
        });
        const data = await res.json();
        if (res.ok) {
          notify(`✅ Rol de usuario actualizado a "${nuevoRol}".`);
          cargarUsuariosAdmin();
          cargarPartidosProgramadosAdmin();
        } else {
          notify(`❌ Error: ${data.message || data.error}`, 'error');
        }
      } catch (err) {
        notify('❌ Error de comunicación con el servidor', 'error');
      }
    }

    // 12. DESIGNACIÓN DE ÁRBITROS A PARTIDOS PROGRAMADOS
    async function cargarPartidosProgramadosAdmin() {
      if (!ADMIN_TOKEN) return;
      const container = document.getElementById('partidosDesignacionContainer');
      container.innerHTML = '<div class="text-gray-500 text-xs col-span-full text-center py-6">Cargando partidos...</div>';

      try {
        const [resPartidos, resUsuarios] = await Promise.all([
          fetch('/api/admin/partidos-programados', { headers: { 'Authorization': `Bearer ${ADMIN_TOKEN}` } }),
          fetch('/api/admin/usuarios', { headers: { 'Authorization': `Bearer ${ADMIN_TOKEN}` } })
        ]);

        const partidos = await resPartidos.json();
        const usuarios = await resUsuarios.json();

        // Filtrar solo los árbitros disponibles con persona vinculada
        const arbitros = usuarios.filter(u => u.role === 'arbitro' && u.persona);

        if (partidos.length === 0) {
          container.innerHTML = '<div class="text-gray-500 text-xs col-span-full text-center py-6">No hay partidos programados en el sistema.</div>';
          return;
        }

        container.innerHTML = partidos.map(p => {
          const arbitroActualId = p.arbitro_persona_id || '';
          const localNombre = p.equipo_local ? p.equipo_local.nombre : 'Local';
          const visitanteNombre = p.equipo_visitante ? p.equipo_visitante.nombre : 'Visitante';
          const torneoNombre = p.torneo ? p.torneo.nombre : 'Torneo Oficial';
          const canchaNombre = p.cancha ? p.cancha.nombre : 'Cancha Principal';
          const fechaStr = new Date(p.fecha_hora).toLocaleDateString('es-AR', {day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit'});
          const esCerrado = p.estado === 'CERRADO';

          let optionsArbitro = `<option value="">-- Sin Árbitro Asignado --</option>`;
          arbitros.forEach(a => {
            const pers = a.persona;
            const selected = (pers.id === arbitroActualId) ? 'selected' : '';
            optionsArbitro += `<option value="${pers.id}" ${selected}>⚖️ ${pers.nombre} ${pers.apellido} (DNI: ${pers.dni})</option>`;
          });

          return `
            <div class="bg-brand-dark/90 border ${esCerrado ? 'border-brand-green/40' : 'border-brand-border'} rounded-2xl p-4 space-y-3 shadow-lg flex flex-col justify-between">
              <div class="space-y-2">
                <div class="flex justify-between items-center text-[10px]">
                  <span class="text-brand-purple font-bold">Fecha ${p.jornada} • ${torneoNombre}</span>
                  <span class="px-2 py-0.5 rounded-full font-bold uppercase ${esCerrado ? 'bg-brand-green/20 text-brand-green border border-brand-green/30' : 'bg-brand-yellow/20 text-brand-yellow border border-brand-yellow/30'}">
                    ${p.estado}
                  </span>
                </div>

                <div class="flex justify-between items-center text-xs font-black text-white py-1">
                  <span class="text-brand-green">${localNombre}</span>
                  <span class="font-mono text-gray-400 font-bold px-2 py-0.5 bg-black/60 rounded">
                    ${p.goles_local !== null ? `${p.goles_local} - ${p.goles_visitante}` : 'VS'}
                  </span>
                  <span class="text-brand-cyan">${visitanteNombre}</span>
                </div>

                <div class="text-[10px] text-gray-400 space-y-0.5 border-t border-brand-border/40 pt-1.5">
                  <div class="flex items-center space-x-1">
                    <i data-lucide="map-pin" class="w-3 h-3 text-brand-cyan"></i>
                    <span>${canchaNombre}</span>
                  </div>
                  <div>📅 ${fechaStr} hs</div>
                </div>
              </div>

              <!-- SELECTOR DE ÁRBITRO -->
              <div class="pt-2 border-t border-brand-border/40 space-y-1">
                <label class="block text-[10px] text-gray-400 font-semibold">Árbitro Designado:</label>
                <select onchange="asignarArbitroPartido('${p.id}', this.value)" ${esCerrado ? 'disabled' : ''} class="w-full bg-brand-card border border-brand-border rounded-xl px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-brand-red ${esCerrado ? 'opacity-60 cursor-not-allowed' : ''}">
                  ${optionsArbitro}
                </select>
                ${p.arbitro ? `<div class="text-[10px] text-brand-green">Designado: <strong>${p.arbitro.nombre} ${p.arbitro.apellido}</strong></div>` : '<div class="text-[10px] text-yellow-500 italic">Pendiente de árbitro</div>'}
              </div>
            </div>
          `;
        }).join('');
        lucide.createIcons();
      } catch (err) {
        container.innerHTML = '<div class="text-red-400 text-xs col-span-full text-center py-6">Error cargando partidos y árbitros.</div>';
      }
    }

    async function asignarArbitroPartido(partidoId, arbitroPersonaId) {
      if (!ADMIN_TOKEN) return;
      try {
        const res = await fetch(`/api/admin/partidos/${partidoId}/asignar-arbitro`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${ADMIN_TOKEN}`
          },
          body: JSON.stringify({ arbitro_persona_id: arbitroPersonaId || null })
        });
        const data = await res.json();
        if (res.ok) {
          notify('✅ Designación arbitral actualizada correctamente.');
          cargarPartidosProgramadosAdmin();
        } else {
          notify(`❌ Error al asignar árbitro: ${data.message || data.error}`, 'error');
        }
      } catch (err) {
        notify('❌ Error de comunicación con el servidor', 'error');
      }
    }

    // INICIO
    window.addEventListener('DOMContentLoaded', () => {
      lucide.createIcons();
      if (checkAdminAuth()) {
        cargarSedes();
        cargarTorneos();
        cargarCanchasGrid();
        cargarSponsorsList();
        cargarUsuariosAdmin();
      }
    });
  </script>
</body>
</html>
