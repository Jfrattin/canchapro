<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CanchaPro - App Móvil (Jugadores & Torneos)</title>
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
    body { background-color: #040609; font-family: 'Inter', sans-serif; }
    .android-bezel { box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.9), 0 0 25px rgba(0, 255, 136, 0.15); }
    .no-scrollbar::-webkit-scrollbar { display: none; }
  </style>
</head>
<body class="text-gray-100 flex items-center justify-center min-h-screen p-2 md:p-6 select-none">

  <!-- ANDROID PHONE FRAME -->
  <div class="relative w-full max-w-md h-[860px] bg-brand-black border-4 border-[#1f2e46] rounded-[48px] overflow-hidden android-bezel flex flex-col justify-between shadow-2xl">

    <!-- ANDROID STATUS BAR -->
    <div class="px-6 pt-3 pb-2 flex justify-between items-center text-[11px] text-gray-400 bg-brand-dark/95 border-b border-brand-border/40 z-20">
      <span class="font-bold text-white">19:30</span>
      <div class="w-16 h-4 bg-black/60 rounded-full flex items-center justify-center">
        <div class="w-2.5 h-2.5 rounded-full bg-[#111]"></div>
      </div>
      <div class="flex items-center space-x-1.5 text-brand-green">
        <i data-lucide="wifi" class="w-3.5 h-3.5"></i>
        <i data-lucide="battery-charging" class="w-3.5 h-3.5"></i>
      </div>
    </div>

    <!-- NOTIFICATION POPUP -->
    <div id="appToast" class="hidden absolute top-12 left-4 right-4 z-50 p-3 rounded-2xl text-xs font-semibold shadow-2xl transition-all"></div>

    <!-- ================================================================ -->
    <!-- SCREEN CONTENT (SCROLLABLE)                                      -->
    <!-- ================================================================ -->
    <div id="screenContent" class="flex-1 overflow-y-auto no-scrollbar p-4 space-y-5">

      <!-- SCREEN 0: AUTH (LOGIN / REGISTRO) -->
      <div id="authScreen" class="space-y-6 pt-4">
        <div class="text-center space-y-1.5">
          <div class="inline-flex items-center space-x-1.5 bg-brand-green/10 border border-brand-green/30 px-3 py-1 rounded-full text-[10px] font-bold text-brand-green uppercase">
            <span>Pase Digital · CanchaPro</span>
          </div>
          <h2 class="font-outfit font-black text-3xl text-white">Cancha<span class="text-brand-green">Pro</span></h2>
          <p class="text-xs text-gray-400">Inicia sesión o regístrate para jugar.</p>
        </div>

        <!-- INVITE BANNER (SI ACCEDIÓ POR LINK DE INVITACIÓN) -->
        <div id="inviteBannerAlert" class="hidden bg-brand-cyan/20 border border-brand-cyan/50 p-3 rounded-2xl text-xs space-y-1">
          <div class="font-bold text-brand-cyan flex items-center space-x-1">
            <span>⚽ ¡Te han invitado a unirte a un equipo!</span>
          </div>
          <p class="text-gray-300 text-[11px]">Inicia sesión con tu cuenta o regístrate debajo. Al ingresar, quedarás <strong>automáticamente inscripto en el plantel</strong>.</p>
        </div>

        <!-- AUTH TABS -->
        <div class="flex bg-brand-dark p-1 rounded-2xl border border-brand-border text-xs">
          <button id="tabLogin" onclick="switchAuthTab('login')" class="flex-1 py-2 rounded-xl font-bold bg-brand-green text-black transition">Ingresar</button>
          <button id="tabRegister" onclick="switchAuthTab('register')" class="flex-1 py-2 rounded-xl font-bold text-gray-400 hover:text-white transition">Registrarme</button>
        </div>

        <!-- LOGIN FORM -->
        <form id="formLogin" class="space-y-3.5 text-xs">
          <div>
            <label class="block text-gray-400 mb-1">Email o DNI</label>
            <input type="text" id="loginEmail" required placeholder="tu@email.com o DNI"
              class="w-full bg-brand-dark border border-brand-border rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-brand-green">
          </div>
          <div>
            <label class="block text-gray-400 mb-1">Contraseña</label>
            <input type="password" id="loginPass" required placeholder="••••••••"
              class="w-full bg-brand-dark border border-brand-border rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-brand-green">
          </div>
          <button type="submit" class="w-full bg-brand-green hover:bg-brand-green/90 text-black font-extrabold py-3 rounded-xl uppercase tracking-wider transition shadow-lg shadow-brand-green/20">
            Ingresar a CanchaPro
          </button>
        </form>

        <!-- REGISTER FORM -->
        <form id="formRegister" class="hidden space-y-3 text-xs">
          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="block text-gray-400 mb-0.5">Nombre</label>
              <input type="text" id="regNombre" required placeholder="Nombre" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white">
            </div>
            <div>
              <label class="block text-gray-400 mb-0.5">Apellido</label>
              <input type="text" id="regApellido" required placeholder="Apellido" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white">
            </div>
          </div>
          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="block text-gray-400 mb-0.5">DNI *</label>
              <input type="text" id="regDni" required placeholder="12345678" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white">
            </div>
            <div>
              <label class="block text-gray-400 mb-0.5">Grupo Sangre</label>
              <select id="regSangre" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white">
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-gray-400 mb-0.5">Email *</label>
            <input type="email" id="regEmail" required placeholder="tu@email.com" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white">
          </div>
          <div>
            <label class="block text-gray-400 mb-0.5">Contraseña *</label>
            <input type="password" id="regPass" required placeholder="Min 6 caracteres" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white">
          </div>
          <button type="submit" class="w-full bg-brand-green hover:bg-brand-green/90 text-black font-extrabold py-3 rounded-xl uppercase tracking-wider transition shadow-lg shadow-brand-green/20">
            Crear Cuenta & Ficha Médica
          </button>
        </form>
      </div>

      <!-- SCREEN 1: APP HOME (LOGUEADO) -->
      <div id="homeScreen" class="hidden space-y-4">

        <!-- 📢 SPONSOR AD BANNER HEADER (PROPAGANDA DE MONETIZACIÓN) -->
        <div id="sponsorHeaderBanner" class="relative rounded-2xl overflow-hidden border border-brand-yellow/40 bg-gradient-to-r from-amber-950/60 to-brand-card p-3 flex items-center justify-between shadow-lg">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-cover bg-center flex-shrink-0" id="sponsorHeaderImg" style="background-image: url('https://images.unsplash.com/photo-1517838277536-f5f99be501cd?auto=format&fit=crop&w=300&q=80');"></div>
            <div>
              <div class="flex items-center space-x-1.5">
                <span class="text-[9px] font-black text-brand-yellow bg-yellow-950/80 px-1.5 py-0.2 rounded uppercase">Sponsor Oficial</span>
                <span id="sponsorHeaderMarca" class="text-[10px] text-gray-300 font-bold">Gatorade</span>
              </div>
              <h5 id="sponsorHeaderTitulo" class="text-xs font-bold text-white leading-tight">Hidratación Oficial CanchaPro</h5>
            </div>
          </div>
          <a id="sponsorHeaderLink" href="https://gatorade.com" target="_blank" class="text-[10px] bg-brand-yellow text-black font-extrabold px-2.5 py-1.5 rounded-lg flex-shrink-0">
            Ver Promo
          </a>
        </div>

        <!-- USER PROFILE CARD -->
        <div class="flex justify-between items-center bg-brand-dark p-3 rounded-2xl border border-brand-border">
          <div class="flex items-center space-x-2.5">
            <div id="userProfileIcon" class="w-9 h-9 rounded-xl bg-brand-green/20 text-brand-green flex items-center justify-center font-bold text-sm">
              ⚽
            </div>
            <div>
              <div class="flex items-center space-x-1.5">
                <h4 id="userNombreLabel" class="font-bold text-xs text-white">-</h4>
                <span id="userRoleBadge" class="hidden text-[9px] bg-brand-red/20 text-brand-red border border-brand-red/30 px-1.5 py-0.2 rounded-full font-black uppercase">Árbitro</span>
              </div>
              <span id="userDniLabel" class="text-[10px] text-brand-cyan">DNI: -</span>
            </div>
          </div>
          <button onclick="logout()" class="text-[10px] text-gray-400 hover:text-red-400 bg-brand-card px-2.5 py-1.5 rounded-lg border border-brand-border">
            Salir
          </button>
        </div>

        <!-- 🏆 BARRA DE FILTRO MULTI-DEPORTE -->
        <div class="flex items-center space-x-1.5 overflow-x-auto no-scrollbar py-1 text-[11px]">
          <button onclick="filtrarDeporteApp('TODOS')" id="btnDeporteTODOS" class="px-3 py-1 rounded-xl font-bold bg-brand-green text-black flex-shrink-0 transition">
            🏆 Todos
          </button>
          <button onclick="filtrarDeporteApp('FUTBOL')" id="btnDeporteFUTBOL" class="px-3 py-1 rounded-xl font-bold bg-brand-dark hover:bg-brand-card text-gray-300 border border-brand-border flex-shrink-0 transition">
            ⚽ Fútbol
          </button>
          <button onclick="filtrarDeporteApp('PADEL')" id="btnDeportePADEL" class="px-3 py-1 rounded-xl font-bold bg-brand-dark hover:bg-brand-card text-gray-300 border border-brand-border flex-shrink-0 transition">
            🎾 Padel
          </button>
          <button onclick="filtrarDeporteApp('TENIS')" id="btnDeporteTENIS" class="px-3 py-1 rounded-xl font-bold bg-brand-dark hover:bg-brand-card text-gray-300 border border-brand-border flex-shrink-0 transition">
            🎾 Tenis
          </button>
          <button onclick="filtrarDeporteApp('BASQUET')" id="btnDeporteBASQUET" class="px-3 py-1 rounded-xl font-bold bg-brand-dark hover:bg-brand-card text-gray-300 border border-brand-border flex-shrink-0 transition">
            🏀 Básquet
          </button>
          <button onclick="filtrarDeporteApp('VOLEY')" id="btnDeporteVOLEY" class="px-3 py-1 rounded-xl font-bold bg-brand-dark hover:bg-brand-card text-gray-300 border border-brand-border flex-shrink-0 transition">
            🏐 Vóley
          </button>
          <button onclick="filtrarDeporteApp('HOCKEY')" id="btnDeporteHOCKEY" class="px-3 py-1 rounded-xl font-bold bg-brand-dark hover:bg-brand-card text-gray-300 border border-brand-border flex-shrink-0 transition">
            🏑 Hockey
          </button>
        </div>

        <!-- ⚖️ BANNER MODO ÁRBITRO (SOLO SE MUESTRA SI ES ÁRBITRO) -->
        <div id="arbitroCalloutBanner" class="hidden bg-gradient-to-r from-red-950/80 via-brand-card to-brand-dark border-2 border-brand-red/50 rounded-2xl p-3.5 shadow-xl space-y-2">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
              <span class="w-2.5 h-2.5 rounded-full bg-brand-red animate-ping"></span>
              <span class="font-outfit font-black text-xs text-white uppercase tracking-wider">Modo Arbitraje Oficial</span>
            </div>
            <span class="text-[10px] bg-brand-red text-white font-bold px-2 py-0.5 rounded-full">Acta Digital</span>
          </div>
          <p class="text-[11px] text-gray-300">Tienes partidos asignados para dirigir. Ingresa para cargar goles, tarjetas y firmar el acta.</p>
          <button onclick="setAppTab('arbitro')" class="w-full bg-brand-red hover:bg-red-600 text-white font-bold py-2 rounded-xl text-xs transition shadow-lg shadow-brand-red/25 flex items-center justify-center space-x-1.5">
            <i data-lucide="clipboard-edit" class="w-3.5 h-3.5"></i>
            <span>Ir a Partidos Asignados & Cargar Resultado</span>
          </button>
        </div>

        <!-- 🔔 NOTIFICACIONES PARA EL CAPITÁN / AVISOS DE ASISTENCIA Y BAJAS -->
        <div id="notificacionesContainer" class="hidden space-y-2"></div>

        <!-- ========================================== -->
        <!-- TAB 1: INICIO (PROXIMO PARTIDO & ASISTENCIA) -->
        <!-- ========================================== -->
        <div id="tabContentInicio" class="space-y-4">
          <!-- HERO CARD: TU PRÓXIMO PARTIDO (CON FOTO Y UBICACIÓN DE CANCHA) -->
          <div class="bg-gradient-to-br from-brand-card via-brand-dark to-[#101b2b] border-2 border-brand-green/50 rounded-3xl overflow-hidden shadow-xl">
            <!-- CANCHA PHOTO HEADER -->
            <div id="partidoCanchaHeaderImg" class="h-28 bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1529900748604-07564a03e7a6?auto=format&fit=crop&w=800&q=80');">
              <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
              <div class="absolute top-2.5 left-3 flex items-center space-x-2">
                <span class="text-[9px] font-black text-black bg-brand-green px-2 py-0.5 rounded-full uppercase tracking-wider">
                  Tu Próximo Partido del Torneo
                </span>
              </div>
              <div class="absolute bottom-2 left-3 right-3 flex justify-between items-end">
                <div>
                  <span id="partidoCanchaNombre" class="text-xs font-bold text-white">Buscando próximo partido...</span>
                  <div id="partidoUbicacionText" class="text-[10px] text-gray-300 flex items-center space-x-1">
                    <i data-lucide="map-pin" class="w-3 h-3 text-brand-cyan flex-shrink-0"></i>
                    <span class="truncate">Complejo Deportivo</span>
                  </div>
                </div>
                <span id="partidoHoraBadge" class="text-[11px] font-mono font-bold text-brand-green bg-black/60 px-2 py-0.5 rounded-lg border border-brand-green/30">--:-- hs</span>
              </div>
            </div>

            <!-- ENFRENTAMIENTO Y ASISTENCIA -->
            <div class="p-4 space-y-3">
              <div id="partidoEnfrentamiento" class="text-center py-1">
                <span class="text-xs text-gray-400 font-medium">Buscando fixture oficial...</span>
              </div>

              <!-- CONTENEDOR DE ASISTENCIA DINÁMICO -->
              <div id="asistenciaInteractiveContainer" class="pt-2.5 border-t border-brand-border/60 space-y-2">
                <div class="flex justify-between items-center text-xs">
                  <span class="font-bold text-gray-300">¿Asistís este partido?</span>
                  <span id="asistenciaStatusBadge" class="text-[10px] px-2 py-0.5 rounded-full bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">Pendiente</span>
                </div>
                <div class="grid grid-cols-2 gap-2">
                  <button onclick="marcarAsistencia(true)" class="bg-brand-green/20 hover:bg-brand-green/30 text-brand-green border border-brand-green/40 py-2 rounded-xl font-bold text-xs transition flex items-center justify-center space-x-1">
                    <span>✅ Asistiré</span>
                  </button>
                  <button onclick="marcarAsistencia(false)" class="bg-brand-red/20 hover:bg-brand-red/30 text-brand-red border border-brand-red/40 py-2 rounded-xl font-bold text-xs transition flex items-center justify-center space-x-1">
                    <span>❌ No podré ir</span>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- RESUMEN RÁPIDO DE MI EQUIPO Y TORNEO ACTIVO -->
          <div id="resumenInicioCard" class="bg-brand-card border border-brand-border rounded-3xl p-4 space-y-2 text-xs">
            <div class="flex justify-between items-center">
              <span class="text-gray-400 font-medium">Torneo en el que compites:</span>
              <span id="inicioTorneoBadge" class="font-bold text-brand-yellow">Cargando...</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-400 font-medium">Tu equipo:</span>
              <span id="inicioEquipoBadge" class="font-bold text-brand-cyan">Cargando...</span>
            </div>
          </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: JUGADOR (MIS ESTADÍSTICAS INDIVIDUALES) -->
        <!-- ========================================== -->
        <div id="tabContentJugador" class="hidden space-y-4">
          <div class="bg-brand-card border border-brand-border rounded-3xl p-5 space-y-4">
            <div class="flex items-center space-x-3 border-b border-brand-border/60 pb-3">
              <div class="w-12 h-12 rounded-2xl bg-brand-purple/20 text-brand-purple flex items-center justify-center font-bold text-xl">
                👤
              </div>
              <div>
                <h3 id="statsJugadorNombre" class="font-outfit font-extrabold text-base text-white">-</h3>
                <span id="statsJugadorDni" class="text-xs text-brand-cyan">DNI: -</span>
              </div>
            </div>

            <!-- GRID DE MÉTRICAS INDIVIDUALES -->
            <div class="grid grid-cols-2 gap-3">
              <div class="bg-brand-dark p-3 rounded-2xl border border-brand-border space-y-0.5">
                <span class="text-[10px] text-gray-400 font-medium">Partidos Jugados</span>
                <h4 id="statJugadorPJ" class="font-outfit font-extrabold text-2xl text-brand-green">0</h4>
              </div>
              <div class="bg-brand-dark p-3 rounded-2xl border border-brand-border space-y-0.5">
                <span class="text-[10px] text-gray-400 font-medium">Goles Totales</span>
                <h4 id="statJugadorGoles" class="font-outfit font-extrabold text-2xl text-brand-yellow">0</h4>
              </div>
              <div class="bg-brand-dark p-3 rounded-2xl border border-brand-border space-y-0.5">
                <span class="text-[10px] text-gray-400 font-medium">Tarjetas Amarillas</span>
                <h4 id="statJugadorAmarillas" class="font-outfit font-extrabold text-2xl text-yellow-400">0</h4>
              </div>
              <div class="bg-brand-dark p-3 rounded-2xl border border-brand-border space-y-0.5">
                <span class="text-[10px] text-gray-400 font-medium">Tarjetas Rojas</span>
                <h4 id="statJugadorRojas" class="font-outfit font-extrabold text-2xl text-brand-red">0</h4>
              </div>
            </div>

            <!-- ASISTENCIA Y COMPROMISO -->
            <div class="bg-brand-dark/80 p-3 rounded-2xl border border-brand-border/60 space-y-2 text-xs">
              <div class="font-bold text-gray-300 flex justify-between">
                <span>Compromiso de Asistencia</span>
                <span id="statJugadorAsistenciaTasa" class="text-brand-cyan font-mono font-bold">100%</span>
              </div>
              <div class="flex items-center space-x-3 text-[11px] text-gray-400">
                <span>✅ Confirmados: <strong id="statJugadorAsistSi" class="text-brand-green">0</strong></span>
                <span>❌ Faltas informadas: <strong id="statJugadorAsistNo" class="text-brand-red">0</strong></span>
              </div>
            </div>

            <!-- 📄 FICHA DE APTO MÉDICO DIGITAL -->
            <div class="bg-brand-dark/90 p-4 rounded-2xl border border-brand-border space-y-3">
              <div class="flex justify-between items-center border-b border-brand-border/60 pb-2">
                <div class="flex items-center space-x-2">
                  <i data-lucide="file-check" class="w-4 h-4 text-brand-green"></i>
                  <h4 class="font-outfit font-extrabold text-xs text-white">Estado de Apto Médico</h4>
                </div>
                <span id="aptoMedicoBadge" class="text-[10px] px-2.5 py-0.5 rounded-full font-bold bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">⏳ Pendiente</span>
              </div>

              <div id="aptoMedicoInfoText" class="text-[11px] text-gray-300 space-y-1">
                <p>Para estar habilitado a jugar, debes presentar tu apto médico y ser verificado por la Organización.</p>
                <div class="text-[10px] text-gray-400">
                  <span>Obra Social / Prepaga: <strong id="aptoObraSocialLabel" class="text-gray-200">No especificada</strong></span> | 
                  <span>Obs: <strong id="aptoObsLabel" class="text-gray-200">Sin observaciones</strong></span>
                </div>
              </div>

              <!-- FORMULARIO DE ENVÍO / ACTUALIZACIÓN -->
              <form id="formAptoMedico" onsubmit="subirAptoMedico(event)" class="space-y-2.5 pt-1 text-xs">
                <div class="grid grid-cols-2 gap-2">
                  <div>
                    <label class="block text-[10px] text-gray-400 mb-0.5">Obra Social / Prepaga</label>
                    <input type="text" id="aptoInputObraSocial" placeholder="Ej: OSDE / Swiss Medical" class="w-full bg-brand-dark border border-brand-border rounded-xl px-2.5 py-1.5 text-white text-[11px]">
                  </div>
                  <div>
                    <label class="block text-[10px] text-gray-400 mb-0.5">Nota / Observación</label>
                    <input type="text" id="aptoInputObs" placeholder="Ej: Certificado adjunto" class="w-full bg-brand-dark border border-brand-border rounded-xl px-2.5 py-1.5 text-white text-[11px]">
                  </div>
                </div>
                <button type="submit" id="btnSubirApto" class="w-full bg-brand-cyan hover:bg-cyan-400 text-black font-extrabold py-2 rounded-xl text-xs uppercase tracking-wider transition shadow-lg shadow-brand-cyan/20">
                  Subir / Actualizar Ficha Médica
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: EQUIPO (PLANTEL & HISTORIAL PARTIDOS) -->
        <!-- ========================================== -->
        <div id="tabContentEquipo" class="hidden space-y-4">
          <!-- SECCIÓN: MI EQUIPO Y PLANTEL -->
          <div class="bg-brand-card border border-brand-border rounded-3xl p-4 space-y-3">
            <div class="flex justify-between items-center">
              <h3 class="font-outfit font-extrabold text-sm text-white flex items-center space-x-1.5">
                <i data-lucide="shield" class="w-4 h-4 text-brand-cyan"></i>
                <span id="miEquipoTituloHeader">Mi Equipo & Plantel Oficial</span>
              </h3>
              <span id="miEquipoBadgeRol" class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-brand-cyan/20 text-brand-cyan hidden">Capitán</span>
            </div>

            <div id="equipoSection" class="space-y-3 text-xs">
              <div class="text-center py-3 text-gray-400">Cargando datos de tu equipo...</div>
            </div>
          </div>

          <!-- HISTORIAL DE PARTIDOS DEL EQUIPO (RESUMEN ANTERIORES) -->
          <div class="bg-brand-card border border-brand-border rounded-3xl p-4 space-y-3">
            <div class="flex justify-between items-center border-b border-brand-border/60 pb-2">
              <h3 class="font-outfit font-extrabold text-sm text-white flex items-center space-x-1.5">
                <i data-lucide="history" class="w-4 h-4 text-brand-green"></i>
                <span>Historial & Resultados del Equipo</span>
              </h3>
              <span class="text-[10px] text-gray-400">Partidos oficiales</span>
            </div>

            <div id="historialPartidosList" class="space-y-2.5 text-xs">
              <div class="text-center py-4 text-gray-500">Cargando historial de partidos...</div>
            </div>
          </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 4: TORNEOS (TABLA DE POSICIONES & PUNTOS) -->
        <!-- ========================================== -->
        <div id="tabContentTorneo" class="hidden space-y-4">
          <!-- CARD EN QUÉ TORNEO ESTOY ANOTADO -->
          <div class="bg-gradient-to-r from-amber-950/40 via-brand-card to-brand-dark border border-brand-yellow/40 rounded-3xl p-4 space-y-2">
            <div class="flex items-center space-x-2">
              <i data-lucide="trophy" class="w-5 h-5 text-brand-yellow"></i>
              <h4 class="font-outfit font-black text-sm text-white">Torneo Oficial en el que Participas</h4>
            </div>
            <div id="torneoActivoCard" class="p-3 bg-brand-dark/90 rounded-2xl border border-brand-border text-xs space-y-1.5">
              <span class="text-gray-500">Cargando torneo activo...</span>
            </div>
          </div>

          <!-- TABLA DE POSICIONES CON PUNTOS, GOLES Y TARJETAS -->
          <div class="bg-brand-card border border-brand-border rounded-3xl p-4 space-y-3">
            <div class="flex justify-between items-center border-b border-brand-border/60 pb-2">
              <h3 class="font-outfit font-extrabold text-sm text-white flex items-center space-x-1.5">
                <i data-lucide="award" class="w-4 h-4 text-brand-yellow"></i>
                <span>Tabla de Posiciones & Fair Play</span>
              </h3>
              <span class="text-[10px] text-gray-400">En Vivo</span>
            </div>

            <div id="tablaPosicionesContainer" class="overflow-x-auto no-scrollbar">
              <div class="text-center py-4 text-gray-500 text-xs">Cargando tabla de posiciones...</div>
            </div>
          </div>

          <!-- LISTA DE TORNEOS ABIERTOS -->
          <div class="bg-brand-card border border-brand-border rounded-3xl p-4 space-y-3">
            <div class="flex justify-between items-center">
              <h3 class="font-outfit font-extrabold text-sm text-white flex items-center space-x-1.5">
                <i data-lucide="calendar" class="w-4 h-4 text-brand-cyan"></i>
                <span>Otros Torneos Disponibles</span>
              </h3>
            </div>
            <div id="torneosAppList" class="space-y-3 text-xs">
              <span class="text-gray-500 text-xs">Cargando torneos disponibles...</span>
            </div>
          </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 6: PARTIDITOS (ENCUENTROS CASUALES) -->
        <!-- ========================================== -->
        <div id="tabContentPartiditos" class="hidden space-y-4">
          <!-- HERO / BANNER PARTIDITOS -->
          <div class="bg-gradient-to-r from-emerald-950/80 via-brand-card to-brand-dark border-2 border-brand-green/40 rounded-3xl p-4 space-y-2.5 shadow-xl">
            <div class="flex justify-between items-center">
              <div class="flex items-center space-x-2">
                <div class="w-9 h-9 rounded-2xl bg-brand-green/20 text-brand-green flex items-center justify-center text-lg font-bold">
                  ⚽
                </div>
                <div>
                  <h3 class="font-outfit font-black text-sm text-white">Partiditos & Encuentros Casuales</h3>
                  <p class="text-[10px] text-gray-400">Arma un partidito, invita amigos o desafía un rival</p>
                </div>
              </div>
              <span class="text-[9px] bg-brand-green text-black font-extrabold px-2 py-0.5 rounded-full uppercase">Comunidad</span>
            </div>
          </div>

          <!-- BUSCADOR RÁPIDO & BOTÓN CREAR -->
          <div class="space-y-2.5">
            <div class="flex justify-between items-center px-1">
              <h4 class="font-outfit font-extrabold text-xs text-white">Encuentros Disponibles</h4>
              <button onclick="toggleFormPartidito()" class="text-[10px] bg-brand-green hover:bg-brand-green/90 text-black px-3 py-1.5 rounded-xl font-bold transition flex items-center space-x-1">
                <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                <span>+ Crear Partidito</span>
              </button>
            </div>

            <!-- 🔍 INPUT BUSCADOR -->
            <div class="relative">
              <input type="text" id="busquedaPartiditoInput" oninput="filtrarPartiditosPorTexto()" placeholder="🔍 Buscar por título, cancha, dirección..."
                class="w-full bg-brand-dark border border-brand-border rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-brand-green transition">
            </div>
          </div>

          <!-- FORMULARIO DE CREACIÓN DE PARTIDITO -->
          <div id="formCrearPartiditoContainer" class="hidden bg-brand-card border border-brand-green/50 rounded-3xl p-4 space-y-3 shadow-xl">
            <div class="flex justify-between items-center border-b border-brand-border/60 pb-2">
              <h4 class="font-outfit font-black text-xs text-brand-green uppercase tracking-wider">Crear Nuevo Partidito Casual</h4>
              <button onclick="toggleFormPartidito()" class="text-xs text-gray-400 hover:text-white">✕</button>
            </div>

            <form id="formCrearPartidito" onsubmit="guardarPartidito(event)" class="space-y-2.5 text-xs">
              <div>
                <label class="block text-gray-300 mb-0.5">Título del Partido *</label>
                <input type="text" id="partiditoTitulo" required placeholder="Ej: Partidito de los Miércoles / F5 Palermo" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white">
              </div>

              <div class="grid grid-cols-2 gap-2">
                <div>
                  <label class="block text-gray-300 mb-0.5">Deporte *</label>
                  <select id="partiditoDeporte" onchange="actualizarFormatosSegunDeporte()" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white">
                    <option value="FUTBOL">⚽ Fútbol</option>
                    <option value="PADEL">🎾 Padel</option>
                    <option value="TENIS">🎾 Tenis</option>
                    <option value="BASQUET">🏀 Básquet</option>
                    <option value="VOLEY">🏐 Vóley</option>
                    <option value="HOCKEY">🏑 Hockey</option>
                  </select>
                </div>
                <div>
                  <label class="block text-gray-300 mb-0.5">Formato *</label>
                  <select id="partiditoFormato" onchange="actualizarMaxJugadoresSegunFormato()" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white">
                    <option value="F5">Fútbol 5 (10 jug)</option>
                    <option value="F7">Fútbol 7 (14 jug)</option>
                    <option value="F8">Fútbol 8 (16 jug)</option>
                    <option value="F11">Fútbol 11 (22 jug)</option>
                  </select>
                </div>
              </div>

              <div>
                <label class="block text-gray-300 mb-0.5">Cancha *</label>
                <input type="text" id="partiditoCancha" required placeholder="Ej: Cancha 1 / Canchita Palermo" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white">
              </div>

              <!-- UBICACIÓN & PREVISUALIZACIÓN DE GOOGLE MAPS -->
              <div>
                <div class="flex justify-between items-center mb-0.5">
                  <label class="block text-gray-300">Ubicación / Dirección *</label>
                  <span class="text-[9px] text-brand-green font-bold flex items-center space-x-1">
                    <i data-lucide="map-pin" class="w-2.5 h-2.5"></i>
                    <span>Google Maps</span>
                  </span>
                </div>
                <input type="text" id="partiditoUbicacion" required placeholder="Ej: Av. Córdoba 3500, Palermo, CABA"
                  oninput="actualizarMapaPartiditoPreview()" onchange="actualizarMapaPartiditoPreview()"
                  class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white">
                
                <div class="mt-1.5 rounded-xl overflow-hidden border border-brand-border/60 bg-brand-dark h-28">
                  <iframe id="partiditoMapIframe" class="w-full h-full border-0" loading="lazy" allowfullscreen
                    src="https://maps.google.com/maps?q=Av.%20Cordoba%203500,%20Palermo&t=&z=15&ie=UTF8&iwloc=&output=embed">
                  </iframe>
                </div>
              </div>

              <!-- FECHA Y HORA SEPARADAS EN 2 INPUTS -->
              <div class="grid grid-cols-2 gap-2">
                <div>
                  <label class="block text-gray-300 mb-0.5">Fecha del Partido *</label>
                  <input type="date" id="partiditoFecha" required class="w-full bg-brand-dark border border-brand-border rounded-xl px-2.5 py-2 text-white">
                </div>
                <div>
                  <label class="block text-gray-300 mb-0.5">Hora de Inicio *</label>
                  <input type="time" id="partiditoHora" required class="w-full bg-brand-dark border border-brand-border rounded-xl px-2.5 py-2 text-white">
                </div>
              </div>

              <div>
                <label class="block text-gray-300 mb-0.5">Modalidad *</label>
                <select id="partiditoModalidad" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white">
                  <option value="JUGADORES_SUELTOS">Sumar Jugadores Sueltos</option>
                  <option value="DESAFIO_EQUIPOS">Desafío Equipo vs Equipo</option>
                </select>
              </div>

              <div class="grid grid-cols-2 gap-2">
                <div>
                  <label class="block text-gray-300 mb-0.5">Precio Total ($)</label>
                  <input type="number" id="partiditoPrecioTotal" placeholder="25000" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white">
                </div>
                <div>
                  <label class="block text-gray-300 mb-0.5">Max Jugadores</label>
                  <input type="number" id="partiditoMaxJugadores" value="10" min="2" class="w-full bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white">
                </div>
              </div>

              <button type="submit" id="btnGuardarPartidito" class="w-full bg-brand-green hover:bg-brand-green/90 text-black font-extrabold py-2.5 rounded-xl uppercase tracking-wider transition shadow-lg shadow-brand-green/20">
                Publicar Partidito & Generar Link
            </form>
          </div>

          <!-- LISTA DE PARTIDITOS ABIERTOS -->
          <div id="partiditosListContainer" class="space-y-3 text-xs">
            <div class="text-center py-6 text-gray-500">Cargando partiditos disponibles...</div>
          </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 5: ÁRBITRO (PARTIDOS ASIGNADOS & ACTAS) -->
        <!-- ========================================== -->
        <div id="tabContentArbitro" class="hidden space-y-4">
          <!-- CABECERA ÁRBITRO -->
          <div class="bg-gradient-to-r from-red-950/80 to-brand-card border border-brand-red/50 rounded-3xl p-4 space-y-2">
            <div class="flex justify-between items-center">
              <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-xl bg-brand-red/20 text-brand-red flex items-center justify-center font-bold">
                  ⚖️
                </div>
                <div>
                  <h4 class="font-outfit font-black text-sm text-white">Panel de Arbitraje Oficial</h4>
                  <p class="text-[10px] text-gray-400">Designaciones asignadas por la administración</p>
                </div>
              </div>
              <span class="text-[9px] bg-brand-red text-white font-bold px-2 py-0.5 rounded-full">Acta Digital</span>
            </div>
          </div>

          <!-- LISTA DE PARTIDOS ASIGNADOS AL ÁRBITRO -->
          <div class="space-y-3">
            <div class="flex justify-between items-center px-1">
              <span class="font-outfit font-bold text-xs text-gray-300">Tus Partidos para Dirigir</span>
              <button onclick="cargarPartidosArbitro()" class="text-[10px] text-brand-red hover:underline flex items-center space-x-1">
                <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                <span>Actualizar</span>
              </button>
            </div>
            <div id="arbitroPartidosList" class="space-y-3 text-xs">
              <div class="text-center py-6 text-gray-500 text-xs">Buscando designaciones...</div>
            </div>
          </div>
        </div>

        <!-- 📢 SPONSOR FOOTER BANNER -->
        <div id="sponsorFooterBanner" class="p-3 bg-brand-dark border border-brand-border rounded-2xl flex items-center justify-between text-xs">
          <div class="flex items-center space-x-2">
            <span class="text-[9px] bg-white/10 text-gray-300 px-1.5 py-0.5 rounded font-bold uppercase">Publicidad</span>
            <span id="sponsorFooterTexto" class="text-gray-300 font-semibold text-[11px]">Nike Flight - Balón Oficial 2026</span>
          </div>
          <a id="sponsorFooterLink" href="https://nike.com" target="_blank" class="text-brand-cyan hover:underline text-[11px] font-bold">25% OFF</a>
        </div>

      </div>

    </div>

    <!-- BOTTOM ANDROID NAV BAR (ALWAYS VISIBLE & STICKY AT BOTTOM) -->
    <div class="sticky bottom-0 bg-brand-dark/95 backdrop-blur-md px-4 py-2.5 border-t border-brand-border/60 flex justify-around items-center text-gray-400 z-30 shadow-2xl">
      <button onclick="setAppTab('inicio')" id="btnTabInicio" class="flex flex-col items-center text-brand-green transition">
        <i data-lucide="home" class="w-5 h-5"></i>
        <span class="text-[9px] font-bold mt-0.5">Inicio</span>
      </button>
      <button onclick="setAppTab('jugador')" id="btnTabJugador" class="flex flex-col items-center hover:text-white transition">
        <i data-lucide="user" class="w-5 h-5"></i>
        <span class="text-[9px] font-bold mt-0.5">Jugador</span>
      </button>
      <button onclick="setAppTab('equipo')" id="btnTabEquipo" class="flex flex-col items-center hover:text-white transition">
        <i data-lucide="shield" class="w-5 h-5"></i>
        <span class="text-[9px] font-bold mt-0.5">Equipo</span>
      </button>
      <button onclick="setAppTab('partiditos')" id="btnTabPartiditos" class="flex flex-col items-center hover:text-white transition">
        <i data-lucide="dribbble" class="w-5 h-5"></i>
        <span class="text-[9px] font-bold mt-0.5">Partiditos</span>
      </button>
      <button onclick="setAppTab('torneo')" id="btnTabTorneo" class="flex flex-col items-center hover:text-white transition">
        <i data-lucide="trophy" class="w-5 h-5"></i>
        <span class="text-[9px] font-bold mt-0.5">Torneo</span>
      </button>
      <button onclick="setAppTab('arbitro')" id="btnTabArbitro" class="hidden flex-col items-center text-brand-red transition">
        <i data-lucide="award" class="w-5 h-5"></i>
        <span class="text-[9px] font-bold mt-0.5">Árbitro</span>
      </button>
    </div>

    <!-- MODAL PARA CERRAR ACTA DE PARTIDO (ÁRBITRO) -->
    <div id="modalCerrarActa" class="hidden absolute inset-0 z-40 bg-black/85 backdrop-blur-md p-4 overflow-y-auto flex flex-col justify-between">
      <div class="space-y-4">
        <div class="flex justify-between items-center border-b border-brand-border/60 pb-2">
          <div class="flex items-center space-x-2">
            <span class="text-xl">⚖️</span>
            <div>
              <h4 class="font-outfit font-black text-sm text-white">Acta Digital Oficial</h4>
              <span id="actaPartidoHeader" class="text-[10px] text-brand-red font-bold">Cargando datos...</span>
            </div>
          </div>
          <button onclick="cerrarModalActa()" class="w-7 h-7 rounded-full bg-brand-dark border border-brand-border flex items-center justify-center text-gray-400 hover:text-white">
            ✕
          </button>
        </div>

        <!-- MARCADOR GENERAL -->
        <div class="bg-brand-card border border-brand-border rounded-2xl p-3.5 space-y-2.5">
          <span class="text-[10px] font-bold text-gray-400 uppercase">1. Marcador Final Oficial</span>
          <div class="grid grid-cols-2 gap-3 items-center text-center">
            <div class="space-y-1">
              <label id="lblEquipoLocal" class="block text-xs font-bold text-brand-green truncate">Local</label>
              <input type="number" id="actaGolesLocal" min="0" value="0" class="w-full bg-brand-dark border border-brand-border rounded-xl py-2 text-center text-xl font-mono font-black text-white focus:outline-none focus:border-brand-green">
            </div>
            <div class="space-y-1">
              <label id="lblEquipoVisita" class="block text-xs font-bold text-brand-cyan truncate">Visitante</label>
              <input type="number" id="actaGolesVisita" min="0" value="0" class="w-full bg-brand-dark border border-brand-border rounded-xl py-2 text-center text-xl font-mono font-black text-white focus:outline-none focus:border-brand-cyan">
            </div>
          </div>
        </div>

        <!-- REGISTRO DE GOLES CON AUTOR / JUGADOR -->
        <div class="bg-brand-card border border-brand-border rounded-2xl p-3.5 space-y-2.5">
          <div class="flex justify-between items-center">
            <span class="text-[10px] font-bold text-gray-400 uppercase">2. Detalle de Goles (Goleadores)</span>
            <button onclick="agregarFilaGol()" class="text-[10px] bg-brand-green/20 hover:bg-brand-green text-brand-green hover:text-black border border-brand-green/40 px-2 py-0.5 rounded-lg font-bold transition">
              + Agregar Gol
            </button>
          </div>
          <div id="actaGolesLista" class="space-y-2">
            <div class="text-[11px] text-gray-500 italic text-center py-2">No se agregaron goles individuales aún.</div>
          </div>
        </div>

        <!-- REGISTRO DE TARJETAS (AMARILLAS / ROJAS) -->
        <div class="bg-brand-card border border-brand-border rounded-2xl p-3.5 space-y-2.5">
          <div class="flex justify-between items-center">
            <span class="text-[10px] font-bold text-gray-400 uppercase">3. Tarjetas & Disciplina</span>
            <button onclick="agregarFilaTarjeta()" class="text-[10px] bg-brand-red/20 hover:bg-brand-red text-brand-red hover:text-white border border-brand-red/40 px-2 py-0.5 rounded-lg font-bold transition">
              + Tarjeta
            </button>
          </div>
          <div id="actaTarjetasLista" class="space-y-2">
            <div class="text-[11px] text-gray-500 italic text-center py-2">Sin incidencias disciplinarias.</div>
          </div>
        </div>

        <!-- OBSERVACIONES ARBITRALES -->
        <div class="bg-brand-card border border-brand-border rounded-2xl p-3 space-y-1.5">
          <label class="block text-[10px] font-bold text-gray-400 uppercase">4. Informe del Árbitro</label>
          <textarea id="actaObservaciones" rows="2" placeholder="Partido finalizado reglamentariamente sin incidentes." class="w-full bg-brand-dark border border-brand-border rounded-xl p-2 text-xs text-white focus:outline-none focus:border-brand-purple"></textarea>
        </div>
      </div>

      <!-- BOTÓN DE FIRMA Y CIERRE -->
      <div class="pt-3 space-y-2">
        <button onclick="enviarCierreActa()" id="btnEnviarCierreActa" class="w-full bg-gradient-to-r from-red-600 to-brand-red hover:from-brand-red hover:to-red-600 text-white font-black py-3 rounded-2xl text-xs uppercase tracking-wider transition shadow-xl shadow-brand-red/30 flex items-center justify-center space-x-2">
          <i data-lucide="lock" class="w-4 h-4"></i>
          <span>🔒 Firmar Acta Digital & Cerrar Partido</span>
        </button>
      </div>
    </div>

  </div>

  <script>
    let authToken = localStorage.getItem('canchapro_token');
    let currentUser = JSON.parse(localStorage.getItem('canchapro_user') || 'null');
    let currentPartidoId = null;
    let currentEquipoId = localStorage.getItem('canchapro_equipo_id') || null;

    function toast(msg, isErr = false) {
      const t = document.getElementById('appToast');
      t.className = `absolute top-12 left-4 right-4 z-50 p-3 rounded-2xl text-xs font-semibold shadow-2xl transition-all ${
        isErr ? 'bg-brand-red text-white' : 'bg-brand-green text-black'
      }`;
      t.innerText = msg;
      t.classList.remove('hidden');
      setTimeout(() => t.classList.add('hidden'), 5000);
    }

    function switchAuthTab(tab) {
      if (tab === 'login') {
        document.getElementById('formLogin').classList.remove('hidden');
        document.getElementById('formRegister').classList.add('hidden');
        document.getElementById('tabLogin').className = 'flex-1 py-2 rounded-xl font-bold bg-brand-green text-black transition';
        document.getElementById('tabRegister').className = 'flex-1 py-2 rounded-xl font-bold text-gray-400 hover:text-white transition';
      } else {
        document.getElementById('formLogin').classList.add('hidden');
        document.getElementById('formRegister').classList.remove('hidden');
        document.getElementById('tabRegister').className = 'flex-1 py-2 rounded-xl font-bold bg-brand-green text-black transition';
        document.getElementById('tabLogin').className = 'flex-1 py-2 rounded-xl font-bold text-gray-400 hover:text-white transition';
      }
    }

    // LOGIN
    document.getElementById('formLogin').addEventListener('submit', async (e) => {
      e.preventDefault();
      const email = document.getElementById('loginEmail').value;
      const pass = document.getElementById('loginPass').value;

      try {
        const res = await fetch('/api/auth/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email, password: pass })
        });
        const data = await res.json();
        if (res.ok) {
          authToken = data.token;
          currentUser = data;
          localStorage.setItem('canchapro_token', authToken);
          localStorage.setItem('canchapro_user', JSON.stringify(data));
          toast('¡Bienvenido a CanchaPro!');
          initApp();
        } else {
          toast(data.message || 'Credenciales inválidas', true);
        }
      } catch (err) {
        toast('Error conectando a la API', true);
      }
    });

    // REGISTRO
    document.getElementById('formRegister').addEventListener('submit', async (e) => {
      e.preventDefault();
      const payload = {
        nombre: document.getElementById('regNombre').value,
        apellido: document.getElementById('regApellido').value,
        dni: document.getElementById('regDni').value,
        grupo_sanguineo: document.getElementById('regSangre').value,
        email: document.getElementById('regEmail').value.trim(),
        password: document.getElementById('regPass').value,
      };

      const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
      if (!emailRegex.test(payload.email)) {
        return toast('Por favor ingresa un email válido (ejemplo: usuario@dominio.com)', true);
      }

      try {
        const res = await fetch('/api/auth/register', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (res.ok) {
          authToken = data.token;
          currentUser = data;
          localStorage.setItem('canchapro_token', authToken);
          localStorage.setItem('canchapro_user', JSON.stringify(data));
          toast('¡Cuenta y Ficha Médica creadas exitosamente!');
          initApp();
        } else {
          toast(data.message || 'Error en registro', true);
        }
      } catch (err) {
        toast('Error conectando a la API', true);
      }
    });

    // CARGAR NOTIFICACIONES PARA EL CAPITÁN / AVISOS DE BAJAS
    async function cargarNotificaciones() {
      if (!authToken) return;
      try {
        const res = await fetch('/api/partidos/notificaciones', {
          headers: { 'Authorization': `Bearer ${authToken}` }
        });
        const notifs = await res.json();
        const container = document.getElementById('notificacionesContainer');
        if (!container) return;

        const sinLeer = notifs.filter(n => !n.leida);
        if (sinLeer.length === 0) {
          container.classList.add('hidden');
          container.innerHTML = '';
          return;
        }

        container.classList.remove('hidden');
        container.innerHTML = `
          <div class="bg-gradient-to-r from-red-950/80 to-brand-card border border-brand-red/50 rounded-2xl p-3.5 shadow-xl space-y-2">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-2">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-red opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-brand-red"></span>
                </span>
                <span class="font-outfit font-extrabold text-xs text-white uppercase tracking-wider">Avisos del Capitán (${sinLeer.length})</span>
              </div>
              <span class="text-[10px] text-brand-red font-semibold">Requiere Cambio</span>
            </div>
            <div class="space-y-1.5 max-h-40 overflow-y-auto pr-1">
              ${sinLeer.map(n => `
                <div class="bg-brand-dark/90 p-2.5 rounded-xl border border-brand-border/60 flex items-start justify-between space-x-2 text-[11px]">
                  <div class="space-y-0.5 flex-1">
                    <div class="font-bold ${n.tipo === 'BAJA_JUGADOR' ? 'text-red-400' : 'text-brand-green'}">${n.titulo}</div>
                    <p class="text-gray-300 leading-tight">${n.mensaje}</p>
                  </div>
                  <button onclick="marcarNotifLeida('${n.id}')" class="text-[10px] bg-brand-card hover:bg-brand-border px-2 py-1 rounded-lg text-gray-300 flex-shrink-0 transition">
                    Entendido
                  </button>
                </div>
              `).join('')}
            </div>
          </div>
        `;
        lucide.createIcons();
      } catch (err) {
        console.error('Error cargando notificaciones:', err);
      }
    }

    async function marcarNotifLeida(notifId) {
      try {
        await fetch(`/api/partidos/notificaciones/${notifId}/leer`, {
          method: 'POST',
          headers: { 'Authorization': `Bearer ${authToken}` }
        });
        cargarNotificaciones();
      } catch (e) {}
    }

    // CARGAR PRÓXIMO PARTIDO CON FOTO Y UBICACIÓN DE CANCHA
    async function cargarProximoPartido() {
      try {
        const res = await fetch('/api/partidos/proximo', {
          headers: { 'Authorization': `Bearer ${authToken}` }
        });
        const p = await res.json();
        const enf = document.getElementById('partidoEnfrentamiento');
        const containerAsistencia = document.getElementById('asistenciaInteractiveContainer');
        
        if (res.ok && p.id) {
          currentPartidoId = p.id;
          const cancha = p.cancha || {};
          const fotoCancha = cancha.foto_url || 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?auto=format&fit=crop&w=800&q=80';
          const ubicacionCancha = cancha.ubicacion || (cancha.sede ? cancha.sede.direccion : 'Av. Libertador 4500');
          const horaStr = new Date(p.fecha_hora).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) + ' hs';

          document.getElementById('partidoCanchaHeaderImg').style.backgroundImage = `url('${fotoCancha}')`;
          document.getElementById('partidoCanchaNombre').innerText = cancha.nombre || 'Cancha Principal';
          document.getElementById('partidoUbicacionText').innerHTML = `
            <i data-lucide="map-pin" class="w-3 h-3 text-brand-cyan flex-shrink-0"></i>
            <span class="truncate">${ubicacionCancha}</span>
          `;
          document.getElementById('partidoHoraBadge').innerText = horaStr;

          enf.innerHTML = `
            <div class="flex justify-around items-center py-2">
              <span class="font-outfit font-black text-lg text-brand-green">${p.equipo_local ? p.equipo_local.nombre : 'Local'}</span>
              <span class="text-xs bg-brand-dark px-2.5 py-1 rounded-lg text-gray-400 font-mono font-bold">VS</span>
              <span class="font-outfit font-black text-lg text-brand-cyan">${p.equipo_visitante ? p.equipo_visitante.nombre : 'Visitante'}</span>
            </div>
            <div class="text-[10px] text-gray-400">
              📅 ${new Date(p.fecha_hora).toLocaleDateString('es-AR', {weekday: 'long', day: 'numeric', month: 'long'})}
            </div>
          `;

          // Control de asistencia: si ya respondió, NO vuelve a pedir que se anote
          const miAsist = p.mi_asistencia || {};
          if (miAsist.respondido) {
            if (miAsist.asiste) {
              containerAsistencia.innerHTML = `
                <div class="bg-brand-green/10 border border-brand-green/30 rounded-xl p-3 flex items-center justify-between text-xs">
                  <div class="flex items-center space-x-2 text-brand-green">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    <div>
                      <div class="font-bold">¡Estás confirmado para jugar!</div>
                      <div class="text-[10px] text-gray-300">Tu capitán y compañeros ya cuentan con vos.</div>
                    </div>
                  </div>
                  <button onclick="marcarAsistencia(false)" class="text-[10px] text-gray-400 hover:text-red-400 underline flex-shrink-0">
                    Cancelar
                  </button>
                </div>
              `;
            } else {
              containerAsistencia.innerHTML = `
                <div class="bg-brand-red/10 border border-brand-red/30 rounded-xl p-3 flex items-center justify-between text-xs">
                  <div class="flex items-center space-x-2 text-brand-red">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                    <div>
                      <div class="font-bold">Informaste que NO asistirás</div>
                      <div class="text-[10px] text-gray-300">El capitán ya recibió la notificación para convocar tu reemplazo.</div>
                    </div>
                  </div>
                  <button onclick="marcarAsistencia(true)" class="text-[10px] bg-brand-green/20 text-brand-green border border-brand-green/40 px-2.5 py-1 rounded-lg font-bold">
                    Revertir
                  </button>
                </div>
              `;
            }
          } else {
            containerAsistencia.innerHTML = `
              <div class="flex justify-between items-center text-xs">
                <span class="font-bold text-gray-300">¿Asistís este partido?</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">Pendiente de respuesta</span>
              </div>
              <div class="grid grid-cols-2 gap-2">
                <button onclick="marcarAsistencia(true)" class="bg-brand-green/20 hover:bg-brand-green/30 text-brand-green border border-brand-green/40 py-2 rounded-xl font-bold text-xs transition flex items-center justify-center space-x-1">
                  <span>✅ Asistiré</span>
                </button>
                <button onclick="marcarAsistencia(false)" class="bg-brand-red/20 hover:bg-brand-red/30 text-brand-red border border-brand-red/40 py-2 rounded-xl font-bold text-xs transition flex items-center justify-center space-x-1">
                  <span>❌ No podré ir</span>
                </button>
              </div>
            `;
          }

        } else {
          document.getElementById('partidoCanchaNombre').innerText = 'Sin Partido Programado';
          document.getElementById('partidoUbicacionText').innerHTML = `
            <i data-lucide="map-pin" class="w-3 h-3 text-brand-cyan flex-shrink-0"></i>
            <span class="truncate">Tu Equipo</span>
          `;
          document.getElementById('partidoHoraBadge').innerText = '--:-- hs';

          enf.innerHTML = `
            <div class="text-xs text-gray-400 py-3">
              No tienes partidos oficiales programados con tu equipo.<br>
              <span class="text-brand-yellow font-semibold">¡Anota tu equipo en un torneo para que se sortee el fixture!</span>
            </div>
          `;
          containerAsistencia.innerHTML = '';
        }
        lucide.createIcons();
      } catch (err) {
        console.error('Error cargando próximo partido:', err);
      }
    }

    // MARCAR ASISTENCIA
    async function marcarAsistencia(asistira) {
      if (!currentPartidoId || !currentEquipoId) {
        toast(asistira ? '¡Asistencia confirmada! ✅' : 'Has informado que no podrás asistir ❌');
        cargarProximoPartido();
        return;
      }

      try {
        const res = await fetch(`/api/partidos/${currentPartidoId}/asistencia`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${authToken}`
          },
          body: JSON.stringify({ equipo_id: currentEquipoId, asistira })
        });
        const data = await res.json();
        toast(data.mensaje || 'Asistencia guardada con éxito');
        await cargarProximoPartido();
        await cargarNotificaciones();
      } catch (err) {
        toast('Error actualizando asistencia', true);
      }
    }

    // CARGAR MI EQUIPO Y PLANTEL (CON QUIÉNES JUEGO)
    async function cargarMiEquipo() {
      if (!authToken) return;
      try {
        const res = await fetch('/api/equipos/mi-equipo', {
          headers: { 'Authorization': `Bearer ${authToken}` }
        });
        const data = await res.json();
        const section = document.getElementById('equipoSection');
        const badgeRol = document.getElementById('miEquipoBadgeRol');

        if (!res.ok || !data.equipo) {
          badgeRol.classList.add('hidden');
          section.innerHTML = `
            <div class="text-gray-400 text-[11px]">Aún no formas parte de un equipo:</div>
            <div class="flex space-x-2">
              <input type="text" id="nuevoEquipoNombre" placeholder="Nombre de Equipo (ej: Los Galácticos)" class="flex-1 bg-brand-dark border border-brand-border rounded-xl px-3 py-2 text-white text-xs">
              <button onclick="crearEquipo()" class="bg-brand-cyan text-black font-bold px-3 py-2 rounded-xl text-xs">Crear</button>
            </div>
          `;
          return;
        }

        const eq = data.equipo;
        currentEquipoId = eq.id;
        localStorage.setItem('canchapro_equipo_id', currentEquipoId);

        badgeRol.innerText = data.es_capitan ? '👑 Capitán' : '⚽ Jugador';
        badgeRol.classList.remove('hidden');

        const fullInviteUrl = window.location.origin + '/join/' + (eq.invite_token || '');

        let html = `
          <div class="bg-brand-dark p-3 rounded-2xl border border-brand-cyan/40 space-y-3">
            <div class="flex justify-between items-center">
              <div>
                <span class="font-outfit font-extrabold text-white text-sm">⚽ ${eq.nombre}</span>
                <div class="text-[10px] text-gray-400">Capitán: <strong class="text-white">${eq.capitan ? eq.capitan.nombre + ' ' + eq.capitan.apellido : 'Sin asignar'}</strong></div>
              </div>
              <span class="text-[10px] bg-brand-cyan/20 text-brand-cyan px-2 py-0.5 rounded-full font-bold">
                ${data.total_jugadores} Jugadores
              </span>
            </div>

            <!-- LINK DE INVITACIÓN -->
            <div class="bg-black/50 p-2 rounded-xl space-y-1">
              <div class="text-[10px] text-gray-400">Link para invitar compañeros por WhatsApp:</div>
              <div class="flex items-center space-x-1.5">
                <input type="text" readonly value="${fullInviteUrl}" class="bg-transparent text-[10px] font-mono text-brand-cyan flex-1 focus:outline-none truncate">
                <button onclick="navigator.clipboard.writeText('${fullInviteUrl}'); toast('¡Link copiado al portapapeles!')" class="bg-brand-cyan text-black px-2.5 py-1 rounded-lg text-[10px] font-bold flex-shrink-0">
                  Copiar
                </button>
              </div>
            </div>

            <!-- LISTADO DEL PLANTEL: CON QUIÉNES JUEGO -->
            <div class="space-y-1.5 pt-1">
              <div class="text-[11px] font-bold text-gray-300 flex items-center justify-between">
                <span>Compañeros de Equipo & Plantel</span>
                <span class="text-[10px] text-brand-green font-normal">Apto Médico</span>
              </div>
              <div class="space-y-1 max-h-48 overflow-y-auto pr-1">
        `;

        if (data.plantel && data.plantel.length > 0) {
          data.plantel.forEach(j => {
            html += `
              <div class="flex items-center justify-between bg-brand-card/80 p-2 rounded-xl border border-brand-border/40 text-[11px]">
                <div class="flex items-center space-x-2">
                  <span class="font-mono font-bold text-brand-cyan text-[10px] w-5">#${j.dorsal || '10'}</span>
                  <div>
                    <span class="font-semibold text-white">${j.nombre} ${j.apellido}</span>
                    ${j.es_tu_usuario ? '<span class="text-[9px] bg-brand-purple text-white px-1.5 py-0.2 rounded-full font-bold ml-1">Tú</span>' : ''}
                    <div class="text-[9px] text-gray-400">${j.rol} • ${j.posicion || 'Campo'}</div>
                  </div>
                </div>
                <div class="text-right">
                  <span class="text-[10px] ${j.apto_medico ? 'text-brand-green' : 'text-yellow-400'} font-semibold">
                    ${j.apto_medico ? '✅ Habilitado' : '⏳ En revisión'}
                  </span>
                </div>
              </div>
            `;
          });
        } else {
          html += `<div class="text-gray-500 text-xs italic py-2">No hay compañeros registrados en este equipo aún.</div>`;
        }

        html += `
              </div>
            </div>
          </div>
        `;

        section.innerHTML = html;

        // Actualizar badges en pestaña Inicio
        if (data.torneos && data.torneos.length > 0) {
          const tActivo = data.torneos[0];
          const bTorneo = document.getElementById('inicioTorneoBadge');
          if (bTorneo) bTorneo.innerText = `${tActivo.nombre} (${tActivo.categoria})`;
        } else {
          const bTorneo = document.getElementById('inicioTorneoBadge');
          if (bTorneo) bTorneo.innerText = 'No inscripto aún';
        }

        const bEquipo = document.getElementById('inicioEquipoBadge');
        if (bEquipo) bEquipo.innerText = eq.nombre;

        // Renderizar Historial de partidos anteriores del equipo
        const historialContainer = document.getElementById('historialPartidosList');
        if (historialContainer) {
          if (data.historial_partidos && data.historial_partidos.length > 0) {
            historialContainer.innerHTML = data.historial_partidos.map(p => {
              const esVictoria = p.resultado === 'VICTORIA';
              const esDerrota = p.resultado === 'DERROTA';
              const badgeClass = esVictoria 
                ? 'bg-brand-green/20 text-brand-green border border-brand-green/40'
                : (esDerrota ? 'bg-brand-red/20 text-brand-red border border-brand-red/40' : 'bg-brand-yellow/20 text-brand-yellow border border-brand-yellow/40');
              const resultadoText = p.resultado === 'PENDIENTE' ? 'Por disputarse' : `${p.resultado} (${p.goles_favor} - ${p.goles_contra})`;

              return `
                <div class="bg-brand-dark p-3 rounded-2xl border border-brand-border/60 space-y-1.5">
                  <div class="flex justify-between items-center text-[10px]">
                    <span class="text-brand-purple font-bold">Fecha ${p.jornada} • ${p.torneo_nombre}</span>
                    <span class="text-[9px] px-2 py-0.5 rounded-full font-bold uppercase ${badgeClass}">${resultadoText}</span>
                  </div>
                  <div class="flex justify-between items-center text-xs font-bold text-white">
                    <span>${p.condicion === 'LOCAL' ? eq.nombre : p.rival_nombre}</span>
                    <span class="font-mono text-brand-cyan text-sm">${p.goles_favor !== null ? `${p.goles_favor} - ${p.goles_contra}` : 'VS'}</span>
                    <span>${p.condicion === 'VISITANTE' ? eq.nombre : p.rival_nombre}</span>
                  </div>
                  <div class="flex justify-between items-center text-[10px] text-gray-400 pt-1 border-t border-brand-border/30">
                    <span class="flex items-center space-x-1">
                      <i data-lucide="map-pin" class="w-3 h-3 text-brand-cyan"></i>
                      <span>${p.cancha_nombre} (${p.cancha_ubicacion})</span>
                    </span>
                    <span>${new Date(p.fecha_hora).toLocaleDateString('es-AR', {day: 'numeric', month: 'short'})}</span>
                  </div>
                </div>
              `;
            }).join('');
          } else {
            historialContainer.innerHTML = `
              <div class="text-gray-500 text-xs italic py-3 text-center">
                Aún no hay partidos registrados en el historial para este equipo.
              </div>
            `;
          }
        }

        // Renderizar Torneo Oficial activo en pestaña Torneo
        const torneoActivoCard = document.getElementById('torneoActivoCard');
        if (torneoActivoCard) {
          if (data.torneos && data.torneos.length > 0) {
            const t = data.torneos[0];
            torneoActivoCard.innerHTML = `
              <div class="flex justify-between items-start">
                <div>
                  <h5 class="font-outfit font-black text-white text-sm">${t.nombre}</h5>
                  <div class="text-[10px] text-gray-400">${t.categoria} • Formato: ${t.formato_juego}</div>
                </div>
                <span class="text-[9px] bg-brand-yellow/20 text-brand-yellow font-bold px-2 py-0.5 rounded-full">Inscripto Oficial</span>
              </div>
              <p class="text-[11px] text-gray-300 pt-1">${t.descripcion || 'Torneo oficial de fútbol amateur.'}</p>
            `;
            cargarTablaPosicionesTorneo(t.id);
          } else {
            torneoActivoCard.innerHTML = `
              <div class="text-gray-400 text-xs">
                Tu equipo aún no está inscripto en ningún torneo.<br>
                <span class="text-brand-yellow font-semibold">Elige uno de los torneos disponibles debajo e inscríbete.</span>
              </div>
            `;
          }
        }

        lucide.createIcons();
      } catch (err) {
        console.error('Error cargando mi equipo:', err);
      }
    }

    // CARGAR TABLA DE POSICIONES Y FAIR PLAY DE UN TORNEO
    async function cargarTablaPosicionesTorneo(torneoId) {
      try {
        const res = await fetch(`/api/torneos/${torneoId}/tabla`);
        const data = await res.json();
        const container = document.getElementById('tablaPosicionesContainer');
        const tabla = data.tabla || [];

        if (tabla.length === 0) {
          container.innerHTML = `<div class="text-center py-4 text-gray-500 text-xs">No hay partidos cerrados todavía para calcular la tabla.</div>`;
          return;
        }

        let html = `
          <table class="w-full text-left border-collapse text-[11px]">
            <thead>
              <tr class="border-b border-brand-border/60 text-gray-400 text-[10px] uppercase">
                <th class="py-2 px-2">#</th>
                <th class="py-2 px-2">Equipo</th>
                <th class="py-2 px-1 text-center">PJ</th>
                <th class="py-2 px-1 text-center">DG</th>
                <th class="py-2 px-1 text-center" title="Tarjetas Amarillas">🟨</th>
                <th class="py-2 px-1 text-center" title="Tarjetas Rojas">🟥</th>
                <th class="py-2 px-2 text-right font-bold text-white">PTS</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-brand-border/30">
        `;

        tabla.forEach((row, idx) => {
          const esMiEquipo = currentEquipoId && row.equipo_id === currentEquipoId;
          html += `
            <tr class="${esMiEquipo ? 'bg-brand-purple/20 font-bold text-white' : 'text-gray-300 hover:bg-brand-dark/40'}">
              <td class="py-2 px-2 font-mono text-gray-400">${idx + 1}</td>
              <td class="py-2 px-2 flex items-center space-x-1.5 truncate">
                <span class="truncate">${row.equipo_nombre}</span>
                ${esMiEquipo ? '<span class="text-[9px] bg-brand-cyan text-black px-1.5 py-0.2 rounded font-black">TÚ</span>' : ''}
              </td>
              <td class="py-2 px-1 text-center text-gray-400">${row.pj}</td>
              <td class="py-2 px-1 text-center font-mono ${row.dg > 0 ? 'text-brand-green' : (row.dg < 0 ? 'text-brand-red' : 'text-gray-400')}">${row.dg > 0 ? `+${row.dg}` : row.dg}</td>
              <td class="py-2 px-1 text-center text-yellow-400 font-mono">${row.amarillas || 0}</td>
              <td class="py-2 px-1 text-center text-red-500 font-mono">${row.rojas || 0}</td>
              <td class="py-2 px-2 text-right font-black text-brand-green text-xs">${row.pts}</td>
            </tr>
          `;
        });

        html += `
            </tbody>
          </table>
        `;
        container.innerHTML = html;
      } catch (err) {
        console.error('Error cargando tabla de posiciones:', err);
      }
    }

    // CREAR EQUIPO & OBTENER LINK DE WHATSAPP
    async function crearEquipo() {
      const nombre = document.getElementById('nuevoEquipoNombre').value;
      if (!nombre) return toast('Ingresa el nombre de tu equipo', true);

      try {
        const res = await fetch('/api/equipos', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${authToken}`
          },
          body: JSON.stringify({ nombre })
        });
        const data = await res.json();
        if (res.ok) {
          currentEquipoId = data.equipo.id;
          localStorage.setItem('canchapro_equipo_id', currentEquipoId);
          toast('🎉 ¡Equipo creado con éxito!');
          cargarMiEquipo();
          cargarTorneosApp();
        } else {
          toast(data.message || 'Error creando equipo', true);
        }
      } catch (err) {
        toast('Error conectando a la API', true);
      }
    }

    // CARGAR TORNEOS CON FOTO, CATEGORÍA, UBICACIÓN Y DESCRIPCIÓN
    async function cargarTorneosApp() {
      try {
        const res = await fetch('/api/torneos');
        const raw = await res.json();
        const torneos = Array.isArray(raw) ? raw : (raw.data?.data || raw.data || []);
        const container = document.getElementById('torneosAppList');
        
        if (torneos.length === 0) {
          container.innerHTML = '<div class="text-gray-500 italic text-xs">No hay torneos abiertos aún en la base de datos.</div>';
          return;
        }

        container.innerHTML = '';
        torneos.forEach(t => {
          const foto = t.foto_url || 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?auto=format&fit=crop&w=800&q=80';
          const ubicacion = t.ubicacion || (t.sede ? `${t.sede.nombre}, ${t.sede.direccion}` : 'Palermo Central');
          const descripcion = t.descripcion || 'Torneo oficial de fútbol amateur.';
          const listas = t.listas_buena_fe || t.listasBuenaFe || [];
          const numEquipos = (t.equipos && t.equipos.length > 0) ? t.equipos.length : listas.length;

          container.innerHTML += `
            <div class="bg-brand-dark border border-brand-border rounded-2xl overflow-hidden shadow-lg">
              <div class="h-28 bg-cover bg-center relative" style="background-image: url('${foto}');">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                <div class="absolute top-2 left-2 flex space-x-1.5">
                  <span class="text-[9px] font-extrabold px-2 py-0.5 rounded-full bg-brand-purple text-white uppercase">${t.categoria}</span>
                  <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-black/60 text-brand-cyan uppercase">${t.formato_juego}</span>
                </div>
                <div class="absolute bottom-2 left-3 right-3">
                  <h5 class="font-outfit font-extrabold text-sm text-white">${t.nombre}</h5>
                </div>
              </div>

              <div class="p-3 space-y-2">
                <div class="flex items-center space-x-1 text-gray-300 text-[10px]">
                  <i data-lucide="map-pin" class="w-3 h-3 text-brand-cyan flex-shrink-0"></i>
                  <span class="truncate">${ubicacion}</span>
                </div>

                <p class="text-gray-400 text-[11px] leading-relaxed line-clamp-2">
                  ${descripcion}
                </p>

                <!-- LISTA DE EQUIPOS E INSCRIPTOS DEL TORNEO -->
                <div class="bg-brand-dark/90 p-2.5 rounded-xl border border-brand-border/40 space-y-1.5">
                  <span class="text-[10px] font-bold text-gray-300 flex items-center space-x-1">
                    <i data-lucide="shield" class="w-3 h-3 text-brand-cyan"></i>
                    <span>Equipos Inscriptos (${numEquipos}):</span>
                  </span>
                  <div class="space-y-1 max-h-32 overflow-y-auto no-scrollbar">
                    ${(t.equipos && t.equipos.length > 0) ? t.equipos.map(eq => {
                      const lMatch = listas.find(l => l.equipo_id === eq.id);
                      const jugList = (lMatch && lMatch.jugadores) ? lMatch.jugadores.map(j => j.persona).filter(Boolean) : [];
                      return `
                        <div class="bg-brand-dark px-2.5 py-1.5 rounded-lg border border-brand-border/30 text-[10px] space-y-0.5">
                          <div class="flex justify-between items-center text-white font-bold">
                            <span>🛡️ ${eq.nombre}</span>
                            <span class="text-[9px] text-gray-400 font-normal">${jugList.length} jug.</span>
                          </div>
                          ${(jugList.length > 0) ? `
                            <div class="text-[9.5px] text-gray-300 pl-2 border-l border-brand-cyan/40 space-y-0.5 mt-1">
                              ${jugList.map(p => `<div>• ${p.nombre} ${p.apellido}</div>`).join('')}
                            </div>
                          ` : ''}
                        </div>
                      `;
                    }).join('') : '<div class="text-[10px] text-gray-500 italic py-0.5">No hay equipos inscriptos aún.</div>'}
                  </div>
                </div>

                <div class="pt-2 border-t border-brand-border/40 flex justify-between items-center">
                  <span class="text-[10px] text-gray-400">Cupos: <strong>${numEquipos} / ${t.max_equipos}</strong></span>
                  <button onclick="inscribirEquipoTorneo('${t.id}')" class="bg-brand-green/20 hover:bg-brand-green text-brand-green hover:text-black border border-brand-green/40 px-3 py-1.5 rounded-xl font-bold text-[10px] transition">
                    Inscribir Equipo
                  </button>
                </div>
              </div>
            </div>
          `;
        });
        lucide.createIcons();
      } catch (err) {
        console.error('Error cargando torneos en app:', err);
      }
    }

    async function inscribirEquipoTorneo(torneoId) {
      if (!currentEquipoId) return toast('Crea tu equipo arriba primero para poder inscribirte', true);

      try {
        const res = await fetch(`/api/torneos/${torneoId}/inscribir`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${authToken}`
          },
          body: JSON.stringify({ equipo_id: currentEquipoId })
        });
        const data = await res.json();
        if (res.ok) {
          toast(data.mensaje || '¡Equipo inscripto en el torneo con éxito!');
          cargarTorneosApp();
          cargarProximoPartido();
        } else {
          toast(data.error || 'No se pudo inscribir', true);
        }
      } catch (err) {
        toast('Error conectando a la API', true);
      }
    }

    // NAVEGACIÓN ENTRE LAS SECCIONES DE LA APP: Inicio, Jugador, Equipo, Partiditos, Torneo, Árbitro
    function setAppTab(tab) {
      document.getElementById('tabContentInicio').classList.add('hidden');
      document.getElementById('tabContentJugador').classList.add('hidden');
      document.getElementById('tabContentEquipo').classList.add('hidden');
      document.getElementById('tabContentPartiditos').classList.add('hidden');
      document.getElementById('tabContentTorneo').classList.add('hidden');
      document.getElementById('tabContentArbitro').classList.add('hidden');

      const tabs = ['Inicio', 'Jugador', 'Equipo', 'Partiditos', 'Torneo', 'Arbitro'];
      tabs.forEach(t => {
        const btn = document.getElementById(`btnTab${t}`);
        if (btn) btn.className = btn.id === 'btnTabArbitro' 
          ? (currentUser && currentUser.user && currentUser.user.role === 'arbitro' ? 'flex flex-col items-center text-gray-400 hover:text-white transition' : 'hidden')
          : 'flex flex-col items-center text-gray-400 hover:text-white transition';
      });

      if (tab === 'inicio') {
        document.getElementById('tabContentInicio').classList.remove('hidden');
        document.getElementById('btnTabInicio').className = 'flex flex-col items-center text-brand-green transition';
        cargarProximoPartido();
        cargarNotificaciones();
      } else if (tab === 'jugador') {
        document.getElementById('tabContentJugador').classList.remove('hidden');
        document.getElementById('btnTabJugador').className = 'flex flex-col items-center text-brand-purple transition';
        cargarMisEstadisticas();
      } else if (tab === 'equipo') {
        document.getElementById('tabContentEquipo').classList.remove('hidden');
        document.getElementById('btnTabEquipo').className = 'flex flex-col items-center text-brand-cyan transition';
        cargarMiEquipo();
      } else if (tab === 'partiditos') {
        document.getElementById('tabContentPartiditos').classList.remove('hidden');
        document.getElementById('btnTabPartiditos').className = 'flex flex-col items-center text-brand-green font-bold transition';
        cargarPartiditosApp();
      } else if (tab === 'torneo') {
        document.getElementById('tabContentTorneo').classList.remove('hidden');
        document.getElementById('btnTabTorneo').className = 'flex flex-col items-center text-brand-yellow transition';
        cargarTorneosApp();
      } else if (tab === 'arbitro') {
        document.getElementById('tabContentArbitro').classList.remove('hidden');
        const btnA = document.getElementById('btnTabArbitro');
        if (btnA) btnA.className = 'flex flex-col items-center text-brand-red font-bold transition';
        cargarPartidosArbitro();
      }
      lucide.createIcons();
    }

    let currentSportFilter = 'TODOS';

    function getSportBadge(deporte) {
      const dep = (deporte || 'FUTBOL').toUpperCase();
      if (dep === 'PADEL') return '🎾 Padel';
      if (dep === 'TENIS') return '🎾 Tenis';
      if (dep === 'BASQUET') return '🏀 Básquet';
      if (dep === 'VOLEY') return '🏐 Vóley';
      if (dep === 'HOCKEY') return '🏑 Hockey';
      return '⚽ Fútbol';
    }

    function filtrarDeporteApp(deporte) {
      currentSportFilter = deporte;
      const sports = ['TODOS', 'FUTBOL', 'PADEL', 'TENIS', 'BASQUET', 'VOLEY', 'HOCKEY'];
      sports.forEach(s => {
        const btn = document.getElementById(`btnDeporte${s}`);
        if (btn) {
          if (s === deporte) {
            btn.className = 'px-3 py-1 rounded-xl font-bold bg-brand-green text-black flex-shrink-0 transition';
          } else {
            btn.className = 'px-3 py-1 rounded-xl font-bold bg-brand-dark hover:bg-brand-card text-gray-300 border border-brand-border flex-shrink-0 transition';
          }
        }
      });
      cargarPartiditosApp();
      cargarTorneosApp();
    }

    function toggleFormPartidito() {
      const c = document.getElementById('formCrearPartiditoContainer');
      c.classList.toggle('hidden');
    }

    function actualizarFormatosSegunDeporte() {
      const dep = document.getElementById('partiditoDeporte').value;
      const selectFormato = document.getElementById('partiditoFormato');
      
      let options = '';
      if (dep === 'PADEL') {
        options = '<option value="DOBLES">Padel Dobles (4 jug)</option><option value="SINGLES">Padel Singles (2 jug)</option>';
      } else if (dep === 'TENIS') {
        options = '<option value="SINGLES">Tenis Singles (2 jug)</option><option value="DOBLES">Tenis Dobles (4 jug)</option>';
      } else if (dep === 'BASQUET') {
        options = '<option value="3X3">Básquet 3x3 (6 jug)</option><option value="5X5">Básquet 5x5 (10 jug)</option>';
      } else if (dep === 'VOLEY') {
        options = '<option value="6X6">Vóley 6x6 (12 jug)</option><option value="BEACH 2X2">Vóley Playa 2x2 (4 jug)</option>';
      } else if (dep === 'HOCKEY') {
        options = '<option value="H7">Hockey 7 (14 jug)</option><option value="H11">Hockey 11 (22 jug)</option>';
      } else {
        options = '<option value="F5">Fútbol 5 (10 jug)</option><option value="F7">Fútbol 7 (14 jug)</option><option value="F8">Fútbol 8 (16 jug)</option><option value="F11">Fútbol 11 (22 jug)</option>';
      }

      selectFormato.innerHTML = options;
      actualizarMaxJugadoresSegunFormato();
    }

    function actualizarMaxJugadoresSegunFormato() {
      const fmt = document.getElementById('partiditoFormato').value;
      const inputMax = document.getElementById('partiditoMaxJugadores');
      if (fmt === 'SINGLES') inputMax.value = 2;
      else if (fmt === 'DOBLES' || fmt === 'BEACH 2X2') inputMax.value = 4;
      else if (fmt === '3X3') inputMax.value = 6;
      else if (fmt === 'F5' || fmt === '5X5') inputMax.value = 10;
      else if (fmt === '6X6') inputMax.value = 12;
      else if (fmt === 'F7' || fmt === 'H7') inputMax.value = 14;
      else if (fmt === 'F8') inputMax.value = 16;
      else if (fmt === 'F11' || fmt === 'H11') inputMax.value = 22;
      else inputMax.value = 10;
    }

    let partiditosDataArr = [];

    function actualizarMapaPartiditoPreview() {
      const ubicacion = document.getElementById('partiditoUbicacion').value;
      const iframe = document.getElementById('partiditoMapIframe');
      if (ubicacion && iframe) {
        iframe.src = `https://maps.google.com/maps?q=${encodeURIComponent(ubicacion)}&t=&z=15&ie=UTF8&iwloc=&output=embed`;
      }
    }

    function filtrarPartiditosPorTexto() {
      const query = (document.getElementById('busquedaPartiditoInput').value || '').toLowerCase().trim();
      if (!query) {
        renderPartiditosCards(partiditosDataArr);
        return;
      }

      const filtrados = partiditosDataArr.filter(e => {
        const titulo = (e.titulo || '').toLowerCase();
        const cancha = (e.cancha_nombre || '').toLowerCase();
        const ubica = (e.ubicacion || '').toLowerCase();
        const creador = e.creador ? `${e.creador.nombre} ${e.creador.apellido}`.toLowerCase() : '';
        return titulo.includes(query) || cancha.includes(query) || ubica.includes(query) || creador.includes(query);
      });

      renderPartiditosCards(filtrados);
    }

    async function cargarPartiditosApp() {
      const container = document.getElementById('partiditosListContainer');
      container.innerHTML = '<div class="text-center py-6 text-gray-500">Cargando partiditos disponibles...</div>';

      try {
        const url = currentSportFilter === 'TODOS' ? '/api/encuentros-casuales' : `/api/encuentros-casuales?deporte=${currentSportFilter}`;
        const res = await fetch(url);
        partiditosDataArr = await res.json();

        if (!Array.isArray(partiditosDataArr)) partiditosDataArr = [];
        renderPartiditosCards(partiditosDataArr);
      } catch (err) {
        container.innerHTML = '<div class="text-center py-6 text-red-400">Error al cargar encuentros casuales.</div>';
      }
    }

    function renderPartiditosCards(list) {
      const container = document.getElementById('partiditosListContainer');
      if (!list || list.length === 0) {
        container.innerHTML = `
          <div class="bg-brand-card/90 border border-brand-border/80 rounded-3xl p-6 text-center space-y-3 shadow-xl">
            <div class="w-14 h-14 mx-auto rounded-3xl bg-brand-green/10 border border-brand-green/30 text-brand-green flex items-center justify-center text-3xl font-bold">
              ⚽
            </div>
            <div class="space-y-1">
              <h4 class="font-outfit font-extrabold text-white text-sm">No hay partidos que coincidan</h4>
              <p class="text-[11px] text-gray-400">¡Sé el primero en armar un partidito en tu cancha favorita y compartir el link!</p>
            </div>
            <button onclick="toggleFormPartidito()" class="inline-flex items-center space-x-1.5 bg-brand-green hover:bg-brand-green/90 text-black font-extrabold px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition hover:scale-[1.02] active:scale-[0.98] cursor-pointer shadow-lg shadow-brand-green/20">
              <i data-lucide="plus-circle" class="w-4 h-4"></i>
              <span>+ Crear Partidito Ahora</span>
            </button>
          </div>
        `;
        lucide.createIcons();
        return;
      }

      container.innerHTML = list.map(e => {
        const jugAnotados = e.jugadores ? e.jugadores.length : 0;
        const creadorNombre = e.creador ? `${e.creador.nombre} ${e.creador.apellido}` : 'Organizador';
        const fechaObj = new Date(e.fecha_hora);
        const fechaStr = fechaObj.toLocaleDateString('es-AR', { day: '2-digit', month: '2-digit' });
        const horaStr = fechaObj.toLocaleTimeString('es-AR', { hour: '2-digit', minute: '2-digit' });
        const shareUrl = e.share_url || `${window.location.origin}/app?casual_invite=${e.share_token}`;
        const sportBadge = getSportBadge(e.deporte);

        const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(e.ubicacion || e.cancha_nombre)}`;
        const yaAnotado = e.jugadores && currentUser && currentUser.persona && e.jugadores.some(j => j.persona_id === currentUser.persona.id);
        const esCreador = currentUser && currentUser.persona && e.creador_persona_id === currentUser.persona.id;
        const esAdmin = currentUser && currentUser.user && (currentUser.user.role === 'super_admin' || currentUser.user.role === 'organizador');

        return `
          <div class="bg-brand-card border border-brand-border hover:border-brand-green/50 rounded-2xl p-3.5 space-y-2.5 transition shadow-lg">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-2">
              <div class="space-y-1 min-w-0 flex-1">
                <div class="flex items-center space-x-1.5 flex-wrap gap-y-1">
                  <span class="text-[10px] bg-brand-purple/20 text-brand-purple font-bold px-2 py-0.5 rounded-full uppercase">${sportBadge}</span>
                  <span class="text-[10px] bg-brand-green/20 text-brand-green font-bold px-2 py-0.5 rounded-full uppercase">${e.formato}</span>
                  <span class="text-[10px] bg-brand-cyan/20 text-brand-cyan font-bold px-2 py-0.5 rounded-full uppercase">${e.modalidad === 'DESAFIO_EQUIPOS' ? '⚔️ Desafío' : '👥 Abierto'}</span>
                </div>
                <h4 class="font-outfit font-black text-sm text-white break-words">${e.titulo}</h4>
                <a href="${mapsUrl}" target="_blank" class="text-[11px] text-brand-green hover:underline flex items-center space-x-1">
                  <i data-lucide="map-pin" class="w-3 h-3 flex-shrink-0"></i>
                  <span class="truncate">${e.cancha_nombre} - ${e.ubicacion} 📍</span>
                </a>
              </div>
              
              <div class="flex sm:flex-col justify-between items-end w-full sm:w-auto pt-1 sm:pt-0 border-t sm:border-0 border-brand-border/40 text-right">
                <div class="flex items-center space-x-2 sm:space-x-0 sm:flex-col text-right">
                  <span class="font-mono font-bold text-brand-yellow text-xs">📅 ${fechaStr}</span>
                  <span class="font-mono font-bold text-brand-cyan text-xs">⏰ ${horaStr} hs</span>
                </div>
                <div class="text-[10px] text-gray-400">Por: <strong>${creadorNombre}</strong></div>
              </div>
            </div>

            <div class="bg-brand-dark p-2 rounded-xl border border-brand-border/60 flex justify-between items-center text-[11px]">
              <div>
                <span class="text-gray-400">Cupos: </span>
                <strong class="${jugAnotados >= e.max_jugadores ? 'text-brand-red' : 'text-brand-green'}">${jugAnotados} / ${e.max_jugadores}</strong>
              </div>
              <div>
                <span class="text-gray-400">Precio / jug: </span>
                <strong class="text-brand-cyan">$${e.precio_por_jugador || 0}</strong>
              </div>
            </div>

            <!-- LISTA DE JUGADORES INSCRIPTOS EN EL PARTIDITO -->
            <div class="bg-brand-dark/80 p-2.5 rounded-xl border border-brand-border/40 space-y-1.5">
              <div class="flex justify-between items-center text-[10px] text-gray-400 border-b border-brand-border/30 pb-1">
                <span class="font-bold text-gray-300 flex items-center space-x-1">
                  <i data-lucide="users" class="w-3 h-3 text-brand-green"></i>
                  <span>Jugadores Inscriptos (${jugAnotados} / ${e.max_jugadores})</span>
                </span>
                ${esCreador ? '<span class="text-[9px] text-brand-yellow font-bold bg-amber-950/40 border border-brand-yellow/30 px-1.5 py-0.2 rounded">Tú eres el Creador</span>' : ''}
              </div>

              <div class="space-y-1 max-h-36 overflow-y-auto no-scrollbar">
                ${(e.jugadores && e.jugadores.length > 0) ? e.jugadores.map(j => {
                  const pName = j.persona ? `${j.persona.nombre} ${j.persona.apellido}` : 'Jugador';
                  const esCreadorDelMatch = e.creador_persona_id === j.persona_id;
                  const puedeExpulsar = (esCreador || esAdmin) && !esCreadorDelMatch;
                  return `
                    <div class="flex justify-between items-center text-[11px] bg-brand-card/80 px-2.5 py-1 rounded-lg border border-brand-border/30">
                      <div class="flex items-center space-x-1.5 truncate">
                        <span class="text-xs flex-shrink-0">${esCreadorDelMatch ? '👑' : '⚽'}</span>
                        <span class="text-gray-200 font-medium truncate">${pName}</span>
                        ${j.equipo_num === 2 ? '<span class="text-[9px] bg-brand-red/20 text-brand-red px-1.5 py-0.2 rounded font-bold">Rival</span>' : ''}
                      </div>
                      ${puedeExpulsar ? `
                        <button onclick="expulsarJugadorPartidito('${e.id}', '${j.persona_id}', '${pName}')" class="text-[9.5px] text-red-400 hover:text-red-200 font-bold bg-red-950/50 hover:bg-red-900 border border-red-500/40 px-2 py-0.5 rounded-md transition hover:scale-105 cursor-pointer flex-shrink-0 ml-1" title="Expulsar a ${pName} del partido">
                          ✕ Expulsar
                        </button>
                      ` : ''}
                    </div>
                  `;
                }).join('') : '<div class="text-[10px] text-gray-500 italic py-0.5">Aún no hay jugadores anotados.</div>'}
              </div>
            </div>

            <div class="flex justify-between items-center pt-1 border-t border-brand-border/40 gap-1.5 flex-wrap">
              <button onclick="copiarLinkPartidito('${shareUrl}')" class="flex-1 bg-brand-dark hover:bg-brand-border border border-brand-border px-2 py-1.5 rounded-xl text-gray-300 font-bold text-[10px] transition hover:scale-[1.02] active:scale-[0.98] cursor-pointer flex items-center justify-center space-x-1">
                <i data-lucide="share-2" class="w-3 h-3 text-brand-cyan"></i>
                <span>Copiar Link 🔗</span>
              </button>
              
              ${(esCreador || esAdmin) ? `
                <button onclick="eliminarPartidito('${e.id}')" class="bg-brand-red/20 hover:bg-brand-red/30 text-brand-red border border-brand-red/40 px-2.5 py-1.5 rounded-xl font-bold text-[10px] transition hover:scale-[1.02] active:scale-[0.98] cursor-pointer" title="Eliminar este partido">
                  🗑️ Borrar
                </button>
              ` : ''}

              ${yaAnotado ? `
                <span class="bg-green-900/40 text-brand-green border border-brand-green/40 px-3 py-1.5 rounded-xl font-extrabold text-[10px]">
                  ✅ Ya estás anotado
                </span>
              ` : `
                <button onclick="unirseAPartidito('${e.share_token}', 1)" class="flex-1 bg-brand-green hover:bg-brand-green/90 text-black px-2.5 py-1.5 rounded-xl font-extrabold text-[10px] transition hover:scale-[1.02] active:scale-[0.98] cursor-pointer shadow-md shadow-brand-green/20">
                  Unirme 🏆
                </button>
                ${e.modalidad === 'DESAFIO_EQUIPOS' ? `
                  <button onclick="desafiarPartidito('${e.share_token}')" class="flex-1 bg-brand-red hover:bg-red-600 text-white px-2.5 py-1.5 rounded-xl font-extrabold text-[10px] transition hover:scale-[1.02] active:scale-[0.98] cursor-pointer">
                    Desafiar ⚔️
                  </button>
                ` : ''}
              `}
            </div>
          </div>
        `;
      }).join('');

      lucide.createIcons();
    }

    async function eliminarPartidito(encuentroId) {
      if (!confirm('¿Estás seguro de que deseas eliminar este partidito casual?')) return;

      try {
        const res = await fetch(`/api/encuentros-casuales/${encuentroId}`, {
          method: 'DELETE',
          headers: {
            'Authorization': `Bearer ${authToken}`
          }
        });
        const data = await res.json();
        if (res.ok) {
          showToast(data.mensaje || 'Partido eliminado correctamente.');
          cargarPartiditosApp();
        } else {
          showToast(data.error || 'No se pudo eliminar el partido.', 'error');
        }
      } catch (err) {
        showToast('Error de comunicación con el servidor', 'error');
      }
    }

    async function expulsarJugadorPartidito(encuentroId, personaId, personaNombre) {
      if (!confirm(`¿Estás seguro de que deseas expulsar a ${personaNombre} de este partidito?`)) return;

      try {
        const res = await fetch(`/api/encuentros-casuales/${encuentroId}/jugadores/${personaId}`, {
          method: 'DELETE',
          headers: {
            'Authorization': `Bearer ${authToken}`
          }
        });
        const data = await res.json();
        if (res.ok) {
          showToast(data.mensaje || 'Jugador expulsado del partidito.');
          cargarPartiditosApp();
        } else {
          showToast(data.error || 'No se pudo expulsar al jugador.', 'error');
        }
      } catch (err) {
        showToast('Error de comunicación con el servidor', 'error');
      }
    }

    async function guardarPartidito(e) {
      e.preventDefault();
      if (!authToken) return showToast('Inicia sesión para crear un partidito', 'error');

      const btn = document.getElementById('btnGuardarPartidito');
      btn.disabled = true;
      btn.innerText = 'Publicando...';

      const fecha = document.getElementById('partiditoFecha').value;
      const hora = document.getElementById('partiditoHora').value;
      const fechaHoraCombined = `${fecha} ${hora}:00`;

      const payload = {
        titulo: document.getElementById('partiditoTitulo').value,
        deporte: document.getElementById('partiditoDeporte').value,
        cancha_nombre: document.getElementById('partiditoCancha').value,
        ubicacion: document.getElementById('partiditoUbicacion').value,
        fecha_hora: fechaHoraCombined,
        formato: document.getElementById('partiditoFormato').value,
        modalidad: document.getElementById('partiditoModalidad').value,
        precio_total: parseFloat(document.getElementById('partiditoPrecioTotal').value || 0),
        max_jugadores: parseInt(document.getElementById('partiditoMaxJugadores').value || 10)
      };

      try {
        const res = await fetch('/api/encuentros-casuales', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${authToken}`
          },
          body: JSON.stringify(payload)
        });
        const data = await res.json();

        if (res.ok && data.encuentro) {
          showToast(data.mensaje || '¡Partido publicado!');
          toggleFormPartidito();
          document.getElementById('formCrearPartidito').reset();
          cargarPartiditosApp();
          if (data.encuentro.share_url) {
            copiarLinkPartidito(data.encuentro.share_url);
          }
        } else {
          showToast(data.error || 'Error al publicar partido', 'error');
        }
      } catch (err) {
        showToast('Error de comunicación con el servidor', 'error');
      } finally {
        btn.disabled = false;
        btn.innerText = 'Publicar Partidito & Generar Link';
      }
    }

    function copiarLinkPartidito(shareUrl) {
      navigator.clipboard.writeText(shareUrl).then(() => {
        showToast('🔗 ¡Link de invitación copiado al portapapeles! Envíalo por WhatsApp.');
      }).catch(() => {
        prompt('Copia este enlace de invitación:', shareUrl);
      });
    }

    async function unirseAPartidito(token, equipoNum = 1) {
      if (!authToken) return showToast('Debes iniciar sesión para unirte', 'error');

      try {
        const res = await fetch(`/api/encuentros-casuales/token/${token}/unirse`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${authToken}`
          },
          body: JSON.stringify({ equipo_num: equipoNum })
        });
        const data = await res.json();

        if (res.ok) {
          showToast(data.mensaje || '¡Te has anotado en el partidito!');
          cargarPartiditosApp();
        } else {
          showToast(data.error || 'No fue posible unirse', 'error');
        }
      } catch (err) {
        showToast('Error conectando a la API', 'error');
      }
    }

    async function desafiarPartidito(token) {
      if (!authToken) return showToast('Inicia sesión para desafiar', 'error');

      let equipoId = currentEquipoId || null;
      let equipoNombre = null;

      if (!equipoId) {
        equipoNombre = prompt('Ingresa el nombre de tu equipo rival:');
        if (!equipoNombre) return;
      }

      try {
        const res = await fetch(`/api/encuentros-casuales/token/${token}/desafiar`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${authToken}`
          },
          body: JSON.stringify({ equipo_id: equipoId, equipo_nombre: equipoNombre })
        });
        const data = await res.json();

        if (res.ok) {
          showToast(data.mensaje || '⚔️ ¡Desafío aceptado!');
          cargarPartiditosApp();
        } else {
          showToast(data.error || 'Error al desafiar', 'error');
        }
      } catch (err) {
        showToast('Error de comunicación con el servidor', 'error');
      }
    }

    // CARGAR ESTADÍSTICAS DEL JUGADOR & APTO MÉDICO
    async function cargarMisEstadisticas() {
      if (!authToken) return;
      try {
        const res = await fetch('/api/equipos/mis-estadisticas', {
          headers: { 'Authorization': `Bearer ${authToken}` }
        });
        const data = await res.json();
        if (res.ok && data.persona) {
          document.getElementById('statsJugadorNombre').innerText = `${data.persona.nombre} ${data.persona.apellido}`;
          document.getElementById('statsJugadorDni').innerText = `DNI: ${data.persona.dni}`;

          const s = data.estadisticas || {};
          document.getElementById('statJugadorPJ').innerText = s.partidos_jugados || 0;
          document.getElementById('statJugadorGoles').innerText = s.goles_totales || 0;
          document.getElementById('statJugadorAmarillas').innerText = s.tarjetas_amarillas || 0;
          document.getElementById('statJugadorRojas').innerText = s.tarjetas_rojas || 0;
          document.getElementById('statJugadorAsistSi').innerText = s.asistencias_si || 0;
          document.getElementById('statJugadorAsistNo').innerText = s.asistencias_no || 0;

          const totalResp = (s.asistencias_si || 0) + (s.asistencias_no || 0);
          const pct = totalResp > 0 ? Math.round((s.asistencias_si / totalResp) * 100) : 100;
          document.getElementById('statJugadorAsistenciaTasa').innerText = `${pct}%`;

          // Apto Médico rendering
          const ficha = data.persona.ficha_medica;
          const badge = document.getElementById('aptoMedicoBadge');
          const lblObra = document.getElementById('aptoObraSocialLabel');
          const lblObs = document.getElementById('aptoObsLabel');

          if (ficha) {
            if (ficha.apto_fisico_aprobado) {
              badge.className = 'text-[10px] px-2.5 py-0.5 rounded-full font-bold bg-green-500/20 text-brand-green border border-green-500/30';
              badge.innerText = '✅ Habilitado para Jugar';
            } else {
              badge.className = 'text-[10px] px-2.5 py-0.5 rounded-full font-bold bg-yellow-500/20 text-yellow-400 border border-yellow-500/30';
              badge.innerText = '⏳ Pendiente de Aprobación Admin';
            }
            lblObra.innerText = ficha.obra_social_prepaga || 'No especificada';
            lblObs.innerText = ficha.observaciones_medicas || 'Sin observaciones';
            if (document.getElementById('aptoInputObraSocial')) {
              document.getElementById('aptoInputObraSocial').value = ficha.obra_social_prepaga || '';
              document.getElementById('aptoInputObs').value = ficha.observaciones_medicas || '';
            }
          } else {
            badge.className = 'text-[10px] px-2.5 py-0.5 rounded-full font-bold bg-red-500/20 text-brand-red border border-red-500/30';
            badge.innerText = '⚠️ Sin Ficha Presentada';
            lblObra.innerText = 'No especificada';
            lblObs.innerText = 'Ficha no presentada';
          }
        }
      } catch (err) {
        console.error('Error cargando mis estadísticas:', err);
      }
    }

    // SUBIR / ACTUALIZAR APTO MÉDICO
    async function subirAptoMedico(e) {
      e.preventDefault();
      if (!authToken) return;
      const btn = document.getElementById('btnSubirApto');
      btn.disabled = true;
      btn.innerText = 'Guardando...';

      const obraSocial = document.getElementById('aptoInputObraSocial').value;
      const obs = document.getElementById('aptoInputObs').value;

      try {
        const res = await fetch('/api/auth/apto-medico', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${authToken}`
          },
          body: JSON.stringify({
            obra_social_prepaga: obraSocial,
            observaciones_medicas: obs
          })
        });
        const data = await res.json();
        if (res.ok) {
          showToast(data.mensaje || 'Ficha de apto médico guardada con éxito.');
          cargarMisEstadisticas();
        } else {
          showToast(data.error || 'Error al guardar apto médico.', 'error');
        }
      } catch (err) {
        showToast('Error de conexión al enviar el apto médico.', 'error');
      } finally {
        btn.disabled = false;
        btn.innerText = 'Subir / Actualizar Ficha Médica';
      }
    }

    // CARGAR SPONSORS ACTIVOS (PROPAGANDAS)
    async function cargarSponsorsApp() {
      try {
        const res = await fetch('/api/sponsors');
        const sponsors = await res.json();
        if (sponsors && sponsors.length > 0) {
          const sHeader = sponsors.find(s => s.posicion === 'header') || sponsors[0];
          if (sHeader) {
            document.getElementById('sponsorHeaderMarca').innerText = sHeader.marca;
            document.getElementById('sponsorHeaderTitulo').innerText = sHeader.titulo;
            if (sHeader.banner_url) {
              document.getElementById('sponsorHeaderImg').style.backgroundImage = `url('${sHeader.banner_url}')`;
            }
            if (sHeader.link_destino) {
              document.getElementById('sponsorHeaderLink').href = sHeader.link_destino;
            }
          }

          const sFooter = sponsors.find(s => s.posicion === 'footer' || s.posicion === 'feed') || sponsors[sponsors.length - 1];
          if (sFooter) {
            document.getElementById('sponsorFooterTexto').innerText = `${sFooter.marca} - ${sFooter.titulo}`;
            if (sFooter.link_destino) {
              document.getElementById('sponsorFooterLink').href = sFooter.link_destino;
            }
          }
        }
      } catch (err) {
        console.error('Error cargando sponsors:', err);
      }
    }

    function logout() {
      localStorage.removeItem('canchapro_token');
      localStorage.removeItem('canchapro_user');
      localStorage.removeItem('canchapro_equipo_id');
      location.reload();
    }

    // ========================================================
    // FUNCIONALIDAD DEL ÁRBITRO: PARTIDOS ASIGNADOS & ACTA DIGITAL
    // ========================================================
    let arbitroPartidosData = [];
    let partidoActivoActa = null;
    let golesActaArr = [];
    let tarjetasActaArr = [];

    async function cargarPartidosArbitro() {
      if (!authToken) return;
      const listContainer = document.getElementById('arbitroPartidosList');
      listContainer.innerHTML = '<div class="text-center py-6 text-gray-500 text-xs">Cargando tus designaciones arbitrales...</div>';

      try {
        const res = await fetch('/api/arbitro/mis-partidos', {
          headers: { 'Authorization': `Bearer ${authToken}` }
        });
        const data = await res.json();
        arbitroPartidosData = data.partidos || [];

        if (arbitroPartidosData.length === 0) {
          listContainer.innerHTML = `
            <div class="bg-brand-card border border-brand-border rounded-2xl p-5 text-center space-y-2">
              <span class="text-2xl">⏳</span>
              <div class="font-bold text-white text-xs">No tienes partidos asignados actualmente</div>
              <p class="text-[11px] text-gray-400">El Super Admin te asignará partidos oficiales en el fixture de cada torneo.</p>
            </div>
          `;
          return;
        }

        listContainer.innerHTML = arbitroPartidosData.map(p => {
          const local = p.equipo_local || {};
          const visita = p.equipo_visitante || {};
          const cancha = p.cancha || {};
          const torneo = p.torneo || {};
          const esCerrado = p.estado === 'CERRADO';
          const fechaStr = new Date(p.fecha_hora).toLocaleDateString('es-AR', {weekday: 'short', day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit'});

          return `
            <div class="bg-brand-card border ${esCerrado ? 'border-brand-green/40' : 'border-brand-red/40'} rounded-3xl p-4 space-y-3 shadow-lg">
              <div class="flex justify-between items-center text-[10px]">
                <span class="text-brand-purple font-bold">Fecha ${p.jornada} • ${torneo.nombre || 'Torneo'}</span>
                <span class="px-2 py-0.5 rounded-full font-bold uppercase ${esCerrado ? 'bg-brand-green/20 text-brand-green border border-brand-green/40' : 'bg-brand-red/20 text-brand-red border border-brand-red/40'}">
                  ${esCerrado ? 'Acta Firmada (Cerrado)' : 'Por Dirigir'}
                </span>
              </div>

              <div class="bg-brand-dark p-3 rounded-2xl border border-brand-border/60 text-center space-y-1">
                <div class="flex justify-around items-center text-sm font-black text-white">
                  <span class="text-brand-green flex-1 truncate">${local.nombre || 'Local'}</span>
                  <span class="font-mono text-xs px-2.5 py-1 rounded-lg bg-black/60 text-gray-300 font-bold mx-2">
                    ${p.goles_local !== null ? `${p.goles_local} - ${p.goles_visitante}` : 'VS'}
                  </span>
                  <span class="text-brand-cyan flex-1 truncate">${visita.nombre || 'Visitante'}</span>
                </div>
                <div class="text-[10px] text-gray-400 pt-1 flex justify-center items-center space-x-1">
                  <i data-lucide="map-pin" class="w-3 h-3 text-brand-cyan"></i>
                  <span>${cancha.nombre || 'Cancha Central'}</span>
                  <span>•</span>
                  <span>📅 ${fechaStr} hs</span>
                </div>
              </div>

              <div>
                ${esCerrado
                  ? `<div class="w-full bg-brand-green/10 text-brand-green border border-brand-green/30 py-2 rounded-xl text-center text-[11px] font-bold">
                       ✓ Partido Finalizado & Acta Cerrada Oficialmente
                     </div>`
                  : `<button onclick="abrirModalActa('${p.id}')" class="w-full bg-gradient-to-r from-red-600 to-brand-red hover:from-brand-red hover:to-red-600 text-white font-extrabold py-2.5 rounded-xl text-xs uppercase tracking-wider transition shadow-lg shadow-brand-red/25 flex items-center justify-center space-x-1.5">
                       <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                       <span>Cargar Resultado & Cerrar Partido</span>
                     </button>`
                }
              </div>
            </div>
          `;
        }).join('');
        lucide.createIcons();
      } catch (err) {
        listContainer.innerHTML = '<div class="text-center py-6 text-red-400 text-xs">Error al cargar designaciones arbitrales.</div>';
      }
    }

    function abrirModalActa(partidoId) {
      partidoActivoActa = arbitroPartidosData.find(p => p.id === partidoId);
      if (!partidoActivoActa) return;

      document.getElementById('actaPartidoHeader').innerText = `${partidoActivoActa.equipo_local.nombre} vs ${partidoActivoActa.equipo_visitante.nombre}`;
      document.getElementById('lblEquipoLocal').innerText = partidoActivoActa.equipo_local.nombre;
      document.getElementById('lblEquipoVisita').innerText = partidoActivoActa.equipo_visitante.nombre;
      document.getElementById('actaGolesLocal').value = 0;
      document.getElementById('actaGolesVisita').value = 0;
      document.getElementById('actaObservaciones').value = 'Partido finalizado reglamentariamente sin incidentes.';

      golesActaArr = [];
      tarjetasActaArr = [];
      renderGolesActa();
      renderTarjetasActa();

      document.getElementById('modalCerrarActa').classList.remove('hidden');
      lucide.createIcons();
    }

    function cerrarModalActa() {
      document.getElementById('modalCerrarActa').classList.add('hidden');
      partidoActivoActa = null;
    }

    function agregarFilaGol() {
      if (!partidoActivoActa) return;
      golesActaArr.push({
        equipo_id: partidoActivoActa.equipo_local.id,
        autor_persona_id: '',
        minuto: 15
      });
      renderGolesActa();
      recalcularMarcadorDesdeGoles();
    }

    function eliminarFilaGol(idx) {
      golesActaArr.splice(idx, 1);
      renderGolesActa();
      recalcularMarcadorDesdeGoles();
    }

    function renderGolesActa() {
      const cont = document.getElementById('actaGolesLista');
      if (!partidoActivoActa || golesActaArr.length === 0) {
        cont.innerHTML = '<div class="text-[11px] text-gray-500 italic text-center py-2">No se agregaron goles individuales aún.</div>';
        return;
      }

      cont.innerHTML = golesActaArr.map((g, idx) => {
        const esLocal = g.equipo_id === partidoActivoActa.equipo_local.id;
        const jugadoresActuales = esLocal ? (partidoActivoActa.equipo_local.jugadores || []) : (partidoActivoActa.equipo_visitante.jugadores || []);

        let optsJugadores = `<option value="">-- Sin Dueño / Por Reclamar --</option>`;
        jugadoresActuales.forEach(j => {
          const sel = (j.id === g.autor_persona_id) ? 'selected' : '';
          optsJugadores += `<option value="${j.id}" ${sel}>#${j.dorsal || ''} ${j.nombre} ${j.apellido}</option>`;
        });

        return `
          <div class="bg-brand-dark p-2.5 rounded-xl border border-brand-border/60 space-y-1.5 text-[11px]">
            <div class="flex justify-between items-center">
              <span class="font-bold text-brand-green">Gol #${idx + 1}</span>
              <button onclick="eliminarFilaGol(${idx})" class="text-red-400 hover:text-red-300 text-[10px]">Eliminar</button>
            </div>
            <div class="grid grid-cols-3 gap-2">
              <div>
                <label class="block text-[9px] text-gray-400">Equipo</label>
                <select onchange="golesActaArr[${idx}].equipo_id = this.value; renderGolesActa(); recalcularMarcadorDesdeGoles();" class="w-full bg-brand-card border border-brand-border rounded-lg p-1 text-[10px] text-white">
                  <option value="${partidoActivoActa.equipo_local.id}" ${esLocal ? 'selected' : ''}>${partidoActivoActa.equipo_local.nombre}</option>
                  <option value="${partidoActivoActa.equipo_visitante.id}" ${!esLocal ? 'selected' : ''}>${partidoActivoActa.equipo_visitante.nombre}</option>
                </select>
              </div>
              <div>
                <label class="block text-[9px] text-gray-400">Autor (Jugador)</label>
                <select onchange="golesActaArr[${idx}].autor_persona_id = this.value;" class="w-full bg-brand-card border border-brand-border rounded-lg p-1 text-[10px] text-white">
                  ${optsJugadores}
                </select>
              </div>
              <div>
                <label class="block text-[9px] text-gray-400">Minuto</label>
                <input type="number" min="1" max="120" value="${g.minuto || 15}" onchange="golesActaArr[${idx}].minuto = parseInt(this.value);" class="w-full bg-brand-card border border-brand-border rounded-lg p-1 text-[10px] text-center text-white">
              </div>
            </div>
          </div>
        `;
      }).join('');
    }

    function recalcularMarcadorDesdeGoles() {
      if (!partidoActivoActa) return;
      let countLocal = 0;
      let countVisita = 0;
      golesActaArr.forEach(g => {
        if (g.equipo_id === partidoActivoActa.equipo_local.id) countLocal++;
        else countVisita++;
      });
      document.getElementById('actaGolesLocal').value = countLocal;
      document.getElementById('actaGolesVisita').value = countVisita;
    }

    function agregarFilaTarjeta() {
      if (!partidoActivoActa) return;
      tarjetasActaArr.push({
        equipo_id: partidoActivoActa.equipo_local.id,
        persona_id: (partidoActivoActa.equipo_local.jugadores && partidoActivoActa.equipo_local.jugadores[0]) ? partidoActivoActa.equipo_local.jugadores[0].id : '',
        tipo: 'AMARILLA',
        minuto: 30,
        motivo: 'Falta táctica'
      });
      renderTarjetasActa();
    }

    function eliminarFilaTarjeta(idx) {
      tarjetasActaArr.splice(idx, 1);
      renderTarjetasActa();
    }

    function renderTarjetasActa() {
      const cont = document.getElementById('actaTarjetasLista');
      if (!partidoActivoActa || tarjetasActaArr.length === 0) {
        cont.innerHTML = '<div class="text-[11px] text-gray-500 italic text-center py-2">Sin incidencias disciplinarias.</div>';
        return;
      }

      cont.innerHTML = tarjetasActaArr.map((t, idx) => {
        const esLocal = t.equipo_id === partidoActivoActa.equipo_local.id;
        const jugadoresActuales = esLocal ? (partidoActivoActa.equipo_local.jugadores || []) : (partidoActivoActa.equipo_visitante.jugadores || []);

        let optsJugadores = '';
        jugadoresActuales.forEach(j => {
          const sel = (j.id === t.persona_id) ? 'selected' : '';
          optsJugadores += `<option value="${j.id}" ${sel}>#${j.dorsal || ''} ${j.nombre} ${j.apellido}</option>`;
        });

        return `
          <div class="bg-brand-dark p-2.5 rounded-xl border border-brand-border/60 space-y-1.5 text-[11px]">
            <div class="flex justify-between items-center">
              <span class="font-bold ${t.tipo === 'ROJA' ? 'text-red-500' : 'text-yellow-400'}">
                ${t.tipo === 'ROJA' ? '🟥 Tarjeta Roja' : '🟨 Tarjeta Amarilla'}
              </span>
              <button onclick="eliminarFilaTarjeta(${idx})" class="text-red-400 hover:text-red-300 text-[10px]">Eliminar</button>
            </div>
            <div class="grid grid-cols-3 gap-2">
              <div>
                <label class="block text-[9px] text-gray-400">Equipo</label>
                <select onchange="tarjetasActaArr[${idx}].equipo_id = this.value; renderTarjetasActa();" class="w-full bg-brand-card border border-brand-border rounded-lg p-1 text-[10px] text-white">
                  <option value="${partidoActivoActa.equipo_local.id}" ${esLocal ? 'selected' : ''}>${partidoActivoActa.equipo_local.nombre}</option>
                  <option value="${partidoActivoActa.equipo_visitante.id}" ${!esLocal ? 'selected' : ''}>${partidoActivoActa.equipo_visitante.nombre}</option>
                </select>
              </div>
              <div>
                <label class="block text-[9px] text-gray-400">Jugador</label>
                <select onchange="tarjetasActaArr[${idx}].persona_id = this.value;" class="w-full bg-brand-card border border-brand-border rounded-lg p-1 text-[10px] text-white">
                  ${optsJugadores}
                </select>
              </div>
              <div>
                <label class="block text-[9px] text-gray-400">Tipo</label>
                <select onchange="tarjetasActaArr[${idx}].tipo = this.value; renderTarjetasActa();" class="w-full bg-brand-card border border-brand-border rounded-lg p-1 text-[10px] text-white">
                  <option value="AMARILLA" ${t.tipo === 'AMARILLA' ? 'selected' : ''}>🟨 Amarilla</option>
                  <option value="ROJA" ${t.tipo === 'ROJA' ? 'selected' : ''}>🟥 Roja</option>
                </select>
              </div>
            </div>
          </div>
        `;
      }).join('');
    }

    async function enviarCierreActa() {
      if (!partidoActivoActa) return;
      const btn = document.getElementById('btnEnviarCierreActa');
      btn.disabled = true;
      btn.innerText = 'Firmando Acta Digital...';

      const golesLocal = parseInt(document.getElementById('actaGolesLocal').value) || 0;
      const golesVisita = parseInt(document.getElementById('actaGolesVisita').value) || 0;
      const observaciones = document.getElementById('actaObservaciones').value;

      // Filtrar goles y tarjetas con datos válidos
      const golesFormateados = golesActaArr.map(g => ({
        equipo_id: g.equipo_id,
        autor_persona_id: g.autor_persona_id || null,
        minuto: g.minuto || 0
      }));

      const tarjetasFormateadas = tarjetasActaArr.filter(t => t.persona_id).map(t => ({
        equipo_id: t.equipo_id,
        persona_id: t.persona_id,
        tipo: t.tipo || 'AMARILLA',
        minuto: t.minuto || 0,
        motivo: t.motivo || 'Reglamentario'
      }));

      const payload = {
        goles_local: golesLocal,
        goles_visitante: golesVisita,
        observaciones: observaciones,
        goles: golesFormateados,
        tarjetas: tarjetasFormateadas
      };

      try {
        const res = await fetch(`/api/arbitro/partidos/${partidoActivoActa.id}/cerrar`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${authToken}`
          },
          body: JSON.stringify(payload)
        });

        const data = await res.json();
        if (res.ok) {
          toast('🔒 ¡Partido cerrado y Acta Digital firmada oficialmente!');
          cerrarModalActa();
          cargarPartidosArbitro();
          cargarProximoPartido();
          cargarMiEquipo();
        } else {
          toast(data.message || data.error || 'Error al cerrar el partido', true);
        }
      } catch (err) {
        toast('Error de comunicación con el servidor', true);
      } finally {
        btn.disabled = false;
        btn.innerText = '🔒 Firmar Acta Digital & Cerrar Partido';
      }
    }

    async function procesarInviteTokenPostAuth() {
      const token = localStorage.getItem('canchapro_pending_invite');
      if (!token || !authToken) return;

      try {
        const res = await fetch(`/api/equipos/join/${token}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${authToken}`
          }
        });
        const data = await res.json();
        localStorage.removeItem('canchapro_pending_invite');
        if (res.ok) {
          toast(data.mensaje || '¡Te has unido exitosamente al equipo!');
        } else {
          toast(data.error || data.message || 'No se pudo unir al equipo', true);
        }
      } catch (err) {
        console.error('Error procesando invitacion:', err);
      }
    }

    async function initApp() {
      // Chequear si se accedió vía link /join/{token}
      const path = window.location.pathname;
      if (path.includes('/join/')) {
        const token = path.split('/join/')[1];
        if (token) {
          localStorage.setItem('canchapro_pending_invite', token);
        }
      }

      const pendingInvite = localStorage.getItem('canchapro_pending_invite');
      const bannerAlert = document.getElementById('inviteBannerAlert');
      if (pendingInvite && bannerAlert) {
        bannerAlert.classList.remove('hidden');
      }

      if (authToken && currentUser) {
        document.getElementById('authScreen').classList.add('hidden');
        document.getElementById('homeScreen').classList.remove('hidden');

        // Auto-unirse a equipo si venía de un link de invitación
        if (pendingInvite) {
          await procesarInviteTokenPostAuth();
        }

        const p = currentUser.persona;
        if (p) {
          document.getElementById('userNombreLabel').innerText = `${p.nombre} ${p.apellido}`;
          document.getElementById('userDniLabel').innerText = `DNI: ${p.dni}`;
        }

        // Si el usuario tiene rol de árbitro, activar botón de arbitraje y banner
        const u = currentUser.user || {};
        const esArbitro = u.role === 'arbitro';
        const bannerA = document.getElementById('arbitroCalloutBanner');
        const badgeA = document.getElementById('userRoleBadge');
        const btnNavA = document.getElementById('btnTabArbitro');
        const iconPerfil = document.getElementById('userProfileIcon');

        if (esArbitro) {
          if (bannerA) bannerA.classList.remove('hidden');
          if (badgeA) badgeA.classList.remove('hidden');
          if (btnNavA) btnNavA.classList.remove('hidden');
          if (iconPerfil) iconPerfil.innerText = '⚖️';
        } else {
          if (bannerA) bannerA.classList.add('hidden');
          if (badgeA) badgeA.classList.add('hidden');
          if (btnNavA) btnNavA.classList.add('hidden');
          if (iconPerfil) iconPerfil.innerText = '⚽';
        }

        const casualInviteToken = urlParams.get('casual_invite');
        if (casualInviteToken) {
          setAppTab('partiditos');
          unirseAPartidito(casualInviteToken);
        }

        cargarProximoPartido();
        cargarMiEquipo();
        cargarNotificaciones();
        cargarTorneosApp();
        cargarSponsorsApp();
        cargarMisEstadisticas();
        if (esArbitro) {
          cargarPartidosArbitro();
        }
      } else {
        document.getElementById('authScreen').classList.remove('hidden');
        document.getElementById('homeScreen').classList.add('hidden');
      }
    }

    window.addEventListener('DOMContentLoaded', () => {
      initApp();
      lucide.createIcons();
    });
  </script>
</body>
</html>
