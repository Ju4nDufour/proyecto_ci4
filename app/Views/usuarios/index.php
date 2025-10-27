<h2>Usuarios</h2>
<a href="<?= base_url('usuarios/nuevo') ?>" class="btn btn-primary">Nuevo Usuario</a>
<table class="table">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Rol</th>
        <th>Acciones</th>
    </tr>
    <?php foreach($usuarios as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= $u['nombre'] ?></td>
            <td><?= $u['email'] ?></td>
            <td><?= $u['rol'] ?></td>
            <td>
                <a href="<?= base_url('usuarios/editar/'.$u['id']) ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="<?= base_url('usuarios/eliminar/'.$u['id']) ?>" class="btn btn-sm btn-danger">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>