<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <form action="<?= base_url('cursos') ?>" method="get" class="d-flex">
            <input type="text" name="buscar" placeholder="Buscar por nombre o código..." class="form-control me-2">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
        <a href="<?= base_url('cursos/crear') ?>" class="btn btn-primary">+ Nuevo curso</a>
    </div>

    <div class="card p-3">
        <h2>📚 Listado de Cursos</h2>
        <table class="table table-bordered mt-3">
            <thead class="table-light">
                <tr>
                    
                    <th>Nombre</th>
                    
                    <th>Carrera</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($cursos as $curso): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= esc($curso['nombre']) ?></td>
                    <td><?= esc($curso['nombre_carrera']) ?></td>
                    <td>
                        <a href="<?= base_url('cursos/editar/'.$curso['id_curso']) ?>" class="btn btn-sm btn-primary">Editar</a>
                        <form action="<?= base_url('cursos/eliminar/'.$curso['id_curso']) ?>" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar este curso?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($cursos)): ?>
                <tr>
                    <td colspan="5" class="text-center">No se encontraron cursos.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
