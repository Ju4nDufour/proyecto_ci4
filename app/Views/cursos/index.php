<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>


<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h3 mb-0">Cursos</h1>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCurso" data-mode="create">+ Nuevo curso</button>
</div>

<?php if ($ok = session('ok')): ?>
  <div class="alert alert-success"><?= esc($ok) ?></div>
<?php endif; ?>
<?php if ($errors = session('errors')): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<?php foreach ($cursosPorCarrera as $carrera => $cursos): ?>
    <h3><?= esc($carrera) ?></h3>
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Materia</th>
                <th>Carrera</th>
                <th style="width:160px">Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($cursos as $curso): ?>
            <tr>
                <td><?= esc($curso['nombre']) ?></td>
                <td><?= esc($carrera) ?></td>
                <td>
                    <div class="btn-group btn-group-sm">
                      <button
                        class="btn btn-outline-secondary btn-edit"
                        data-bs-toggle="modal" data-bs-target="#modalCurso" data-mode="edit"
                        data-id="<?= esc($curso['id_curso']) ?>"
                        data-nombre="<?= esc($curso['nombre']) ?>"
                        data-carrera="<?= esc($curso['id_carrera']) ?>"
                        title="Editar"
                      ><i class="bi bi-pencil"></i></button>
                      <form method="post" action="<?= site_url('cursos/delete/' . $curso['id_curso']) ?>" onsubmit="return confirm('¿Eliminar curso <?= esc($curso['nombre']) ?>?');" style="display:inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="btn btn-outline-danger" title="Eliminar">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>

<!-- Modal: Crear / Editar Curso (reutilizable) -->
<div class="modal fade" id="modalCurso" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <form class="modal-content needs-validation" method="post" novalidate>
      <?= csrf_field() ?>
      <input type="hidden" name="_method" id="modalMethod" value="POST">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Nuevo curso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
