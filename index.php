<?php
session_start();
include "conexion.php";

$sesionIniciada = isset($_SESSION["usuario_id"]);
$usuario_id = $sesionIniciada ? $_SESSION["usuario_id"] : null;
$usuario_email = $sesionIniciada ? $_SESSION["usuario_email"] : null;

$talleres = $conexion->query("SELECT * FROM talleres");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inscripción a Talleres</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

<nav class="navbar">
  <div class="logo">
    <span class="logo-icon">✦</span>
    <span>Talleres CLHC 2026</span>
  </div>

  <div class="nav-links">
    <a href="#">Inicio</a>
    <a href="#auth-section">Acceso</a>
    <a href="#talleres-section">Talleres</a>
  </div>

  <?php if ($sesionIniciada): ?>
    <div class="nav-user">
      <div>
        <span class="nav-user-label">Sesión iniciada</span>
        <p id="nav-user-email"><?php echo htmlspecialchars($usuario_email); ?></p>
      </div>

      <a href="logout.php" class="nav-logout">Cerrar sesión</a>
    </div>
  <?php endif; ?>
</nav>

<header class="hero <?php echo $sesionIniciada ? 'hero-logged' : ''; ?>">
  <?php if (!$sesionIniciada): ?>
    <div class="hero-card">
      <div class="hero-badge">Registro abierto</div>

      <h1>Inscripción a Talleres</h1>

      <p>
        Consulta los talleres disponibles, inicia sesión y reserva tu lugar de forma rápida.
      </p>

      <a href="#auth-section" class="hero-button">Inscribirme ahora</a>
    </div>
  <?php endif; ?>
</header>

<main class="container">

  <?php if (!$sesionIniciada): ?>
    <section id="auth-section" class="auth-card">
      <div class="section-title">
        <span></span>
        <h2>Acceso de participantes</h2>
      </div>

      <p class="section-description">
        Crea una cuenta para inscribirte a los talleres o inicia sesión si ya tienes registro.
      </p>

      <div class="auth-tabs">
        <button type="button" class="tab-btn active" onclick="mostrarRegistro()">
          Registrarme
        </button>

        <button type="button" class="tab-btn" onclick="mostrarLogin()">
          Iniciar sesión
        </button>
      </div>

      <!-- FORMULARIO DE REGISTRO -->
      <form id="registro-form" action="registrar.php" method="POST">
        <div class="form-grid">

          <div>
            <label>Nombre completo</label>
            <input type="text" name="nombre" placeholder="Ejemplo: Karla Pérez" required>
          </div>

          <div>
            <label>Correo electrónico</label>
            <input type="email" name="email" placeholder="Ejemplo: correo@gmail.com" required>
          </div>

          <div>
            <label>Contraseña</label>
            <input type="password" name="password" placeholder="Mínimo 6 caracteres" required>
          </div>

          <div>
            <label>Edad</label>
            <input type="number" name="edad" placeholder="Ejemplo: 22" min="10" max="100" required>
          </div>

          <div>
            <label>Género</label>
            <select name="genero" required>
              <option value="">Selecciona una opción</option>
              <option value="Mujer">Mujer</option>
              <option value="Hombre">Hombre</option>
              <option value="Prefiero no decirlo">Prefiero no decirlo</option>
            </select>
          </div>

          <div>
            <label>Escolaridad</label>
            <select name="escolaridad" required>
              <option value="">Selecciona una opción</option>
              <option value="Secundaria">Secundaria</option>
              <option value="Bachillerato">Bachillerato</option>
              <option value="Licenciatura">Licenciatura</option>
              <option value="Maestría">Maestría</option>
              <option value="Doctorado">Doctorado</option>
              <option value="Otro">Otro</option>
            </select>
          </div>

          <div>
            <label>Lugar de procedencia</label>
            <input type="text" name="procedencia" placeholder="Ejemplo: Oaxaca, México" required>
          </div>

          <div>
            <label>Institución</label>
            <input type="text" name="institucion" placeholder="Ejemplo: Universidad / Empresa" required>
          </div>

          <div>
            <label>Teléfono</label>
            <input type="text" name="telefono" placeholder="Opcional">
          </div>

        </div>

        <div class="button-group">
          <button type="submit" class="btn-primary">
            Crear cuenta
          </button>
        </div>
      </form>

      <!-- FORMULARIO DE LOGIN -->
      <form id="login-form" action="login.php" method="POST" class="hidden">
        <div class="form-grid login-grid">

          <div>
            <label>Correo electrónico</label>
            <input type="email" name="email" placeholder="Correo registrado" required>
          </div>

          <div>
            <label>Contraseña</label>
            <input type="password" name="password" placeholder="Contraseña" required>
          </div>

        </div>

        <div class="button-group">
          <button type="submit" class="btn-secondary">
            Iniciar sesión
          </button>
        </div>
      </form>
    </section>
  <?php endif; ?>

  <?php if ($sesionIniciada): ?>
    <section id="talleres-section">
      <div class="section-title">
        <span></span>
        <h2>Talleres disponibles</h2>
      </div>

      <p class="section-description">
        Selecciona el taller de tu interés. Si ya estás inscritx, el sistema lo marcará automáticamente.
      </p>

      <div class="talleres-grid">

        <?php while ($taller = $talleres->fetch_assoc()): ?>

          <?php
          $sqlInscrito = "SELECT * FROM inscripciones WHERE usuario_id = ? AND taller_id = ?";
          $stmt = $conexion->prepare($sqlInscrito);
          $stmt->bind_param("ii", $usuario_id, $taller["id"]);
          $stmt->execute();
          $resultadoInscrito = $stmt->get_result();
          $yaInscrito = $resultadoInscrito->num_rows > 0;

          $sqlAsistentes = "SELECT COUNT(*) AS total FROM inscripciones WHERE taller_id = ?";
          $stmtAsistentes = $conexion->prepare($sqlAsistentes);
          $stmtAsistentes->bind_param("i", $taller["id"]);
          $stmtAsistentes->execute();
          $resultadoAsistentes = $stmtAsistentes->get_result();
          $asistentes = $resultadoAsistentes->fetch_assoc()["total"];
          ?>

          <div class="taller">
            <div class="taller-imagen">
              <img 
                src="imge/<?php echo htmlspecialchars($taller['imagen']); ?>" 
                alt="<?php echo htmlspecialchars($taller['nombre']); ?>"
              >
            </div>

            <div class="taller-contenido">
              <?php if ($yaInscrito): ?>
                <span class="estado-inscrito">Ya estás inscritx</span>
              <?php endif; ?>

              <h3><?php echo htmlspecialchars($taller["nombre"]); ?></h3>
              <p><?php echo htmlspecialchars($taller["descripcion"]); ?></p>
              <p><strong>Fecha:</strong> <?php echo htmlspecialchars($taller["fecha"]); ?></p>
              <p><strong>Asistentes inscritos:</strong> <?php echo $asistentes; ?></p>
            </div>

            <div class="taller-acciones">
              <?php if ($yaInscrito): ?>
                <button class="btn-inscrito" disabled>Inscripción activa</button>

                <form action="desinscribirse.php" method="POST">
                  <input type="hidden" name="taller_id" value="<?php echo $taller['id']; ?>">
                  <button type="submit" class="btn-desinscribir">Desinscribirme</button>
                </form>
              <?php else: ?>
                <form action="inscribirse.php" method="POST">
                  <input type="hidden" name="taller_id" value="<?php echo $taller['id']; ?>">
                  <button type="submit">Inscribirme</button>
                </form>
              <?php endif; ?>
            </div>
          </div>

        <?php endwhile; ?>

      </div>
    </section>
  <?php endif; ?>

</main>

<footer>
  <p>Plataforma de inscripción a talleres · 2026</p>
</footer>

<script>
  function mostrarRegistro() {
    document.getElementById("registro-form").classList.remove("hidden");
    document.getElementById("login-form").classList.add("hidden");

    const botones = document.querySelectorAll(".tab-btn");
    botones[0].classList.add("active");
    botones[1].classList.remove("active");
  }

  function mostrarLogin() {
    document.getElementById("registro-form").classList.add("hidden");
    document.getElementById("login-form").classList.remove("hidden");

    const botones = document.querySelectorAll(".tab-btn");
    botones[0].classList.remove("active");
    botones[1].classList.add("active");
  }
</script>

</body>
</html>