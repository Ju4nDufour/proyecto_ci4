<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<?php foreach ($cursosPorCarrera as $carrera => $cursos): ?>
    <h3><?= esc($carrera) ?></h3>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Materias</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach ($cursos as $curso): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= esc($curso['nombre']) ?></td>
                <td>
                    <a href="<?= base_url('cursos/editar/'.$curso['id_curso']) ?>" class="btn btn-sm btn-primary">Editar</a>
                    <form action="<?= base_url('cursos/eliminar/'.$curso['id_curso']) ?>" method="post" style="display:inline" onsubmit="return confirm('¿Eliminar?')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>

<?= $this->endSection() ?>