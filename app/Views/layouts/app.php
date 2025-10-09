<!doctype html>
<html lang="es">
<head>
  <?= csrf_meta() ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($title ?? 'Proyecto BD') ?></title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Tu CSS opcional -->
  <link href="/css/app.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= site_url('/') ?>">Registro Alumnos</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="mainNav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">

        <li class="nav-item"><a class="nav-link" href="<?= site_url('dashboard') ?>">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= site_url('alumnos') ?>">Alumnos</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= site_url('carreras') ?>">Carreras</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= site_url('cursos') ?>">Cursos</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= site_url('profesores') ?>">Profesores</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= site_url('inscripciones') ?>">Inscripciones</a></li>
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

<head>
  <meta charset="utf-8">
  <title><?= esc($title ?? 'Registro') ?></title>
  <link rel="stylesheet" href="<?= base_url('assets/styles.css') ?>">
  <?= csrf_meta() ?>
</head>
