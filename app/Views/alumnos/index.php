<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <h1 class="h2 mb-0 fw-bold">
    <i class="bi bi-mortarboard-fill text-primary me-2"></i>Alumnos
  </h1>
  <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAlumno" data-mode="create">
    <i class="bi bi-plus-circle me-2"></i>Nuevo Alumno
  </button>
</div>

<?php if ($ok = session('ok')): ?>
  <div class="alert alert-success d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i>
    <?= esc($ok) ?>
  </div>
<?php endif; ?>
<?php if ($errors = session('errors')): ?>
  <div class="alert alert-danger d-flex align-items-start">
    <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
    <ul class="mb-0">
      <?php foreach($errors as $e): ?>
        <li><?= esc($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="card shadow border-0">
  <div class="card-header">
    <i class="bi bi-list-ul me-2"></i>Listado de Alumnos
  </div>
  <div class="card-body">

    <form class="row g-2 mb-4" method="get" action="<?= current_url() ?>">
      <div class="col-sm-6 col-md-4">
        <div class="input-group">
          <span class="input-group-text">
            <i class="bi bi-search"></i>
          </span>
          <input name="q" value="<?= esc($q) ?>" class="form-control" placeholder="Buscar por DNI o nombre...">
        </div>
      </div>
      <div class="col-auto">
        <button class="btn btn-primary">
          <i class="bi bi-search me-1"></i>Buscar
        </button>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>DNI</th>
            <th>Nombre y Apellido</th>
            <th>Email</th>
            <th>Fecha Nac.</th>
            <th>Carrera</th>
            <th style="width:160px">Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php if (!empty($alumnos)): foreach($alumnos as $a): ?>
          <tr>
            <td><?= esc($a['id_alumno']) ?></td>
            <td><?= esc($a['dni']) ?></td>
            <td><?= esc($a['nombre']) ?></td>
            <td><?= esc($a['email']) ?></td>
            <td><?= esc($a['fecha_nac']) ?></td>
            <td>
              <span class="badge bg-info"><?= esc($a['carrera']) ?></span>
            </td>
            <td>
              <div class="btn-group btn-group-sm">
                <button
                  class="btn btn-primary btn-edit"
                  data-bs-toggle="modal" data-bs-target="#modalAlumno" data-mode="edit"
                  data-id="<?= esc($a['id_alumno']) ?>"
                  data-dni="<?= esc($a['dni']) ?>"
                  data-nombre="<?= esc($a['nombre']) ?>"
                  data-email="<?= esc($a['email']) ?>"
                  data-fecha="<?= esc($a['fecha_nac']) ?>"
                  data-carrera="<?= esc($a['id_carrera']) ?>"
                >
                  <i class="bi bi-pencil-square me-1"></i>Editar
                </button>

                <form method="post" action="<?= site_url('alumnos/'.$a['id_alumno']) ?>" onsubmit="return confirm('¿Eliminar alumno <?= esc($a['nombre']) ?>?');">
                  <?= csrf_field() ?>
                  <input type="hidden" name="_method" value="DELETE">
                  <button class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i>Eliminar
                  </button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; else: ?>
          <tr>
            <td colspan="7" class="text-center text-muted py-4">
              <i class="bi bi-inbox fs-1 d-block mb-2"></i>
              Sin resultados
            </td>
          </tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div><?= $pager->links('default','default_full') ?></div>
  </div>
</div>

<!-- Modal: Crear / Editar Alumno (reutilizable) -->
<div class="modal fade" id="modalAlumno" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <form class="modal-content needs-validation" method="post" novalidate>
      <?= csrf_field() ?>
      <input type="hidden" name="_method" id="modalMethod" value="POST">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalTitle">
          <i class="bi bi-person-plus-fill me-2"></i>Nuevo Alumno
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body row g-3">
        <div class="col-md-4">
          <label class="form-label">DNI</label>
          <input name="dni" id="dni" type="text" class="form-control" maxlength="8" pattern="\d{8}" required>
          <div class="invalid-feedback">Ingresá 8 dígitos.</div>
        </div>
        <div class="col-md-8">
          <label class="form-label">Nombre y Apellido</label>
          <input name="nombre" id="nombre" type="text" class="form-control" required>
          <div class="invalid-feedback">Campo obligatorio.</div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input name="email" id="email" type="email" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Fecha de nacimiento</label>
          <input name="fecha_nac" id="fecha" type="date" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Carrera</label>
          <select name="id_carrera" id="id_carrera" class="form-select" required>
            <option value="" disabled selected>Elegir...</option>
            <?php foreach($carreras as $c): ?>
              <option value="<?= esc($c['id_carrera']) ?>"><?= esc($c['nombre']) ?></option>
            <?php endforeach; ?>
            <input type="hidden" name="rol_id" value="3">

          </select>
          <div class="invalid-feedback">Seleccioná una carrera.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancelar</button>
        <button class="btn btn-primary" type="submit" id="btnGuardar">Guardar</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Reusar modal para crear/editar
const modal = document.getElementById('modalAlumno');
modal.addEventListener('show.bs.modal', event => {
  const btn = event.relatedTarget;
  const mode = btn.getAttribute('data-mode') || 'create';
  const form = modal.querySelector('form');
  const title = modal.querySelector('#modalTitle');
  const methodInput = modal.querySelector('#modalMethod');

  // Limpio estado
  form.reset();
  form.classList.remove('was-validated');

  if (mode === 'edit') {
    title.textContent = 'Editar alumno';
    methodInput.value = 'PUT';
    const id = btn.dataset.id;
    form.action = "<?= site_url('alumnos') ?>/" + id;

    // Cargo datos
    document.getElementById('dni').value    = btn.dataset.dni || '';
    document.getElementById('nombre').value = btn.dataset.nombre || '';
    document.getElementById('email').value  = btn.dataset.email || '';
    document.getElementById('fecha').value  = btn.dataset.fecha || '';
    document.getElementById('id_carrera').value = btn.dataset.carrera || '';
  } else {
    title.textContent = 'Nuevo alumno';
    methodInput.value = 'POST';
    form.action = "<?= site_url('alumnos') ?>";
  }
});

// Validación Bootstrap
(() => {
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', e => {
      if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>
<?= $this->endSection() ?>
