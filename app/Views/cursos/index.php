<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>


<div class="d-flex align-items-center justify-content-between mb-4">
  <h1 class="h2 mb-0 fw-bold">
    <i class="bi bi-book-fill text-primary me-2"></i>Cursos
  </h1>
  <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCurso" data-mode="create">
    <i class="bi bi-plus-circle me-2"></i>Nuevo Curso
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

<?php foreach ($cursosPorCarrera as $carrera => $cursos): ?>
  <div class="card shadow mb-4">
    <div class="card-header">
      <i class="bi bi-journal-text me-2"></i><?= esc($carrera) ?>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Materia</th>
              <th>Carrera</th>
              <th style="width:160px">Acciones</th>
            </tr>
          </thead>
          <tbody>
          <?php $i = 1; foreach ($cursos as $curso): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= esc($curso['nombre']) ?></td>
              <td>
                <span class="badge bg-info"><?= esc($carrera) ?></span>
              </td>
              <td>
                <div class="btn-group btn-group-sm">
                  <button
                    class="btn btn-primary btn-edit"
                    data-bs-toggle="modal" data-bs-target="#modalCurso" data-mode="edit"
                    data-id="<?= esc($curso['id_curso']) ?>"
                    data-nombre="<?= esc($curso['nombre']) ?>"
                    data-carrera="<?= esc($curso['id_carrera']) ?>"
                  >
                    <i class="bi bi-pencil-square me-1"></i>Editar
                  </button>
                  <form method="post" action="<?= site_url('cursos/delete/' . $curso['id_curso']) ?>" onsubmit="return confirm('¿Eliminar curso <?= esc($curso['nombre']) ?>?');" style="display:inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="btn btn-danger">
                      <i class="bi bi-trash me-1"></i>Eliminar
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<!-- Modal: Crear / Editar Curso (reutilizable) -->
<div class="modal fade" id="modalCurso" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <form class="modal-content needs-validation" method="post" novalidate>
      <?= csrf_field() ?>
      <input type="hidden" name="_method" id="modalMethod" value="POST">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalTitle">
          <i class="bi bi-book-half me-2"></i>Nuevo Curso
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row g-3">
        <div class="col-md-8">
          <label class="form-label">Nombre</label>
          <input name="nombre" id="nombre" type="text" class="form-control" required>
          <div class="invalid-feedback">Campo obligatorio.</div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Carrera</label>
          <select name="id_carrera" id="id_carrera" class="form-select" required>
            <option value="" disabled selected>Elegir...</option>
            <?php foreach($carreras as $c): ?>
              <option value="<?= esc($c['id_carrera']) ?>"><?= esc($c['nombre']) ?></option>
            <?php endforeach; ?>
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

<?= $this->section('scripts') ?>
<script>
// Reusar modal para crear/editar
const modal = document.getElementById('modalCurso');
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
    title.textContent = 'Editar curso';
    methodInput.value = 'PUT';
    const id = btn.dataset.id;
    form.action = "<?= site_url('cursos/update/') ?>" + id;

    // Cargo datos
    document.getElementById('nombre').value    = btn.dataset.nombre || '';
    document.getElementById('id_carrera').value = btn.dataset.carrera || '';
  } else {
    title.textContent = 'Nuevo curso';
    methodInput.value = 'POST';
    form.action = "<?= site_url('cursos/store') ?>";
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

<?= $this->endSection() ?>