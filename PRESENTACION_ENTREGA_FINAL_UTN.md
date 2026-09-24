# 🎓 TRABAJO DE FIN DE CICLO — PRESENTACIÓN Y ENTREGABLE FINAL
## Universidad Tecnológica Nacional · UTN.BA (Centro de e-Learning)
### Curso de Inteligencia Artificial para Programadores

---

## 📋 FICHA TÉCNICA DEL PROYECTO Y AUTOR

* **Integrante:** Joaquín Javier Frattin
* **Rol en el Desarrollo:** *Lead AI Systems Architect & Full-Stack Engineer*
* **Nombre del Proyecto:** CanchaPro Suite
* **Subtítulo:** Plataforma Inteligente de Gestión Deportiva, Automatización de Torneos, Listas de Buena Fe y Arbitraje Digital.

---

## 🔗 ACCESO DIRECTO A ENTREGABLES Y CARPETA DE VIDEOS

> 📁 **Google Drive (2 Videos HD + Documentación):**  
> 🌐 [`https://drive.google.com/drive/folders/1e0qJFQRBQJHwC5OCD73y29pzF-_7ENhr?usp=drive_link`](https://drive.google.com/drive/folders/1e0qJFQRBQJHwC5OCD73y29pzF-_7ENhr?usp=drive_link)
>
> 🌐 **Aplicación Web en Vivo (Render Cloud):** [`https://canchapro-g56l.onrender.com`](https://canchapro-g56l.onrender.com)  
> 💻 **Repositorio GitHub Oficial:** [`https://github.com/Jfrattin/canchapro.git`](https://github.com/Jfrattin/canchapro.git) *(Branch `main`)*

---

## 🔑 CUENTAS DEMO PRECONFIGURADAS PARA EVALUACIÓN EN VIVO

* 👑 **Super Admin / Organizador:** `admin@canchapro.com` | Clave: `password123`
* ⚖️ **Árbitro Oficial:** `arbitro@canchapro.com` | Clave: `password123`
* ⚽ **Jugador / Capitán:** `jugador@canchapro.com` | Clave: `password123`

---

## 1. RESUMEN EJECUTIVO Y PROBLEMÁTICA DE NEGOCIO

En la gestión de torneos amateur existen 4 vulnerabilidades operativas críticas:
1. **Falta de control médico:** Inclusión de jugadores sin apto físico vigente ("jugadores truchos").
2. **Comunicación desarticulada:** Coordinación informal por grupos de WhatsApp.
3. **Disputas en actas arbitrales:** Inconsistencias en goles anotados y tablas de posiciones.
4. **Pérdida de datos históricos:** Torneos archivados en planillas de papel.

**Solución CanchaPro:** Ecosistema dual (App Móvil PWA + Backoffice Web Desktop) asistido por IA con validación de 13 Reglas de Negocio (RN-01 a RN-13), Patrón Trinidad, Lista de Buena Fe inmutable y Consenso Fair Play.

---

## 2. ARQUITECTURA TÉCNICA Y PATRÓN TRINIDAD

El sistema utiliza el **Patrón Trinidad** para garantizar integridad referencial estricta: `1 Usuario` autentica a `1 Persona` (posesora de Ficha Médica cifrada) y asume `N Roles` (Jugador, Capitán, Árbitro, Admin).

### ⚙️ STACK TECNOLÓGICO
* **Frontend:** HTML5 + TailwindCSS + JavaScript ES6 + Lucide Icons (Responsive Dark Mode).
* **Backend:** Laravel 11 (PHP 8.2) + API RESTful asíncrona en contenedores Docker.
* **Persistencia:** PostgreSQL (Supabase / Docker ACID) + Storage cifrado AES-256.
* **Orquestación IA:** Rust Unified MCP Bridge + Groq LPU (Qwen/Llama 3.3) + Google Gemini 2.0.

---

## 3. MÓDULO MULTI-DEPORTE Y PARTIDITOS CASUALES

CanchaPro expandió su cobertura a **6 disciplinas deportivas complejas**: Fútbol (F5, F7, F8, F11), Padel (Dobles/Singles), Tenis, Básquet (3x3/5x5), Vóley y Hockey.

* **Integración con Google Maps:** Previsualización interactiva del predio y cancha.
* **Token Público de Invitación (`/casual/{token}`):** Invitación directa por WhatsApp.
* **Desafío de Equipos Rivales:** Retos formales entre capitanes registrados.
* **Gestión de Plantel:** Expulsión y administración de inscriptos por el creador del encuentro.

---

## 4. MÓDULO DE ARBITRAJE Y PROTOCOLO FAIR PLAY (RN-10)

Los árbitros ingresan con su perfil dedicado para cargar tarjetas, goles y observaciones. Al firmar el acta, el partido queda bloqueado irreversiblemente.

> ⚖️ **PROTOCOLO "GOLES SIN DUEÑO"**
> * Si un gol no se identifica en cancha, se registra como *Gol Sin Dueño*.
> * Notificación inmediata a los capitanes de ambos equipos rivales.
> * Plazo de 48 horas para validación cruzada del autor del gol.
> * **Invariante Matemática:** Los reclamos jamás pueden superar los goles del acta.
> * Vencido el plazo, la IA de CanchaPro asigna el gol automáticamente según el historial.

---

## 5. PRUEBAS AUTOMATIZADAS (PHPUNIT) Y CIBERSEGURIDAD

```text
✓ Registro e inicio de sesion con Patron Trinidad .................. PASS (0.18s)
✓ Creacion y listado de partidito casual multi-deporte ............. PASS (0.02s)
✓ Unirse a partidito casual mediante token criptografico ........... PASS (0.01s)
✓ Carga y aprobacion 1-clic de apto medico por admin ............... PASS (0.01s)
✓ Expulsion de jugador de partidito por creador ................... PASS (0.01s)
✓ Creacion y publicacion de torneo por administrador .............. PASS (0.02s)

ESTADO DE PRUEBAS: 6 PASS / 24 ASERCIONES VERIFICADAS EN INTEGRACIÓN
```

---

## 6. MANUAL DE USO RÁPIDO (GUÍA PASO A PASO)

1. **Inicio de Sesión:** Ingrese a `https://canchapro-g56l.onrender.com` con las cuentas demo.
2. **Modo Jugador:** Vea "Tu Próximo Partido" y arme la Convocatoria de 11 titulares.
3. **Partiditos:** Cree un partidito, vea el mapa en Google Maps y comparta el link `/casual/{token}`.
4. **Backoffice Admin:** Cree torneos, sedes y apruebe Aptos Médicos en 1 clic.
5. **Modo Árbitro:** Anote goles, firme el acta e inicie el protocolo de Goles Sin Dueño.

---

## 7. EVALUACIÓN DE IA LOCAL (EDGE SLM CON OLLAMA)

En canchas o vestuarios sin conectividad 4G/5G, CanchaPro despliega un modelo de lenguaje pequeño (SLM Llama 3.2 3B) ejecutado localmente mediante Ollama, garantizando latencia cero, privacidad absoluta y resiliencia offline a costo $0.
