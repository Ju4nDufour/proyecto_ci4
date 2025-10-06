<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="dashboard">
    <h1>Sistema de Gestión Académica</h1>
<p class="lead">Accedé fácilmente a toda la información de alumnos, carreras, cursos y más.</p>
<footer>
  <p>📧 Contacto: info@universidad.edu</p>
  <p>🌐 Sitio web oficial: www.universidad.edu</p>
</footer>



    <ul>
        <li><a href="<?= base_url('/dashboard') ?>">🏠 Ir al Dashboard</a></li>
        <li><a href="<?= base_url('/alumnos') ?>">👨‍🎓 Ver Alumnos</a></li>
        <li><a href="<?= base_url('/carreras') ?>">🎓 Ver Carreras</a></li>
        <li><a href="<?= base_url('/profesores') ?>">👩‍🏫 Ver Profesores</a></li>
        <li><a href="<?= base_url('/cursos') ?>">📘 Ver Cursos</a></li>
    </ul>
</div>

<?= $this->endSection() ?>
