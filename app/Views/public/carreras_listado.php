<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-3 text-primary">🎓 Carreras disponibles</h2>

    <?php if (!empty($carreras)): ?>
        <div class="row row-cols-1 row-cols-md-2 g-3">
            <?php foreach ($carreras as $c): ?>
                <div class="col">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body">
                            <h5 class="card-title text-success mb-2"><?= esc($c['nombre']) ?></h5>
                            <p class="card-text text-muted mb-0">
                                <strong>Código:</strong> <?= esc($c['codigo']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info mt-3">
            No hay carreras registradas actualmente.
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary">
            ⬅️ Volver al inicio
        </a>
    </div>
</div>

<?= $this->endSection() ?>