<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h3 mb-0">Alumnos</h1>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAlumno" data-mode="create">+ Nuevo alumno</button>
</div>

<?php if ($ok = session('ok')): ?>
  <div class="alert alert-success"><?= esc($ok) ?></div>
<?php endif; ?>
<?php if ($errors = session('errors')): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="card shadow-sm border-0">
  <div class="card-body">

    <form class="row g-2 mb-3" method="get" action="<?= current_url() ?>">
      <div class="col-sm-6 col-md-4">
        <input name="q" value="<?= esc($q) ?>" class="form-control" placeholder="Buscar por DNI o nombre...">
      </div>
      <div class="col-auto"><button class="btn btn-outline-secondary">Buscar</button></div>
    </form>

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>DNI</th><th>Nombre y Apellido</th><th>Email</th><th>Fecha Nac.</th><th>Carrera</th><th style="width:160px">Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php if (!empty($alumnos)): foreach($alumnos as $a): ?>
          <tr>
            <td><?= esc($a['dni']) ?></td>
            <td><?= esc($a['nombre']) ?></td>
            <td><?= esc($a['email']) ?></td>
            <td><?= esc($a['fecha_nac']) ?></td>
            <td><?= esc($a['carrera']) ?></td>
            <td>
              <div class="btn-group btn-group-sm">
                <button
                  class="btn btn-outline-secondary btn-edit"
                  data-bs-toggle="modal" data-bs-target="#modalAlumno" data-mode="edit"
                  data-id="<?= esc($a['id_alumno']) ?>"
                  data-dni="<?= esc($a['dni']) ?>"
                  data-nombre="<?= esc($a['nombre']) ?>"
                  data-email="<?= esc($a['email']) ?>"
                  data-fecha="<?= esc($a['fecha_nac']) ?>"
                  data-carrera="<?= esc($a['id_carrera']) ?>"
                  title="Editar"
                ><i class="bi bi-pencil"></i></button>

                <form method="post" action="<?= site_url('alumnos/'.$a['id_alumno']) ?>" onsubmit="return confirm('¿Eliminar alumno <?= esc($a['nombre']) ?>?');">
                  <?= csrf_field() ?>
                  <input type="hidden" name="_method" value="DELETE">
                  <button class="btn btn-outline-danger" title="Eliminar">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; else: ?>
          <tr><td colspan="6" class="text-center text-muted">Sin resultados</td></tr>
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
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Nuevo alumno</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
