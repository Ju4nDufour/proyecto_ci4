<!doctype html>
<html lang="es">
<head>
  <?= csrf_meta() ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($title ?? 'Proyecto BD') ?></title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome para iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<?php 
  $session = session();
  $isLoggedIn = $session->get('logged_in') === true;
  $username = $session->get('username') ?? 'Usuario';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <!-- Logo/Icono a la izquierda -->
    <a class="navbar-brand fw-bold" href="<?= site_url('/') ?>">
      <i class="fas fa-graduation-cap"></i> Instituto Académico
    </a>
    
    <!-- Botón toggler para móvil -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <!-- Menú a la derecha -->
    <div id="mainNav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        
        <?php if ($isLoggedIn): ?>
          <!-- NAVBAR PARA USUARIOS LOGUEADOS -->
          <li class="nav-item"><a class="nav-link" href="<?= site_url('dashboard') ?>">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= site_url('alumnos') ?>">Alumnos</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= site_url('carreras') ?>">Carreras</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= site_url('cursos') ?>">Cursos</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= site_url('profesores') ?>">Profesores</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= site_url('inscripciones') ?>">Inscripciones</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-user-circle"></i> <?= esc($username) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="<?= site_url('auth/logout') ?>"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
            </ul>
          </li>
        <?php else: ?>
          <!-- NAVBAR PARA USUARIOS NO LOGUEADOS -->
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url('auth/login') ?>">
              <i class="fas fa-sign-in-alt"></i> Login
            </a>
          </li>
        <?php endif; ?>
        
      </ul>
    </div>
  </div>
</nav>

<main class="container py-4">
  <?= $this->renderSection('content') ?>
</main>

<footer class="py-3 mt-4 border-top bg-white">
  <div class="container text-center text-muted small">
    Proyecto BD · CI4 · <?= date('Y') ?>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>