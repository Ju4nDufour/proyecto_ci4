<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <div class="text-center mb-4">
        <p class="text-uppercase text-muted small mb-1">Oferta academica</p>
        <h2 class="fw-semibold">Carreras disponibles</h2>
        <p class="text-muted mb-0">Explora cada propuesta y conoce rapidamente de que se trata.</p>
    </div>

    <?php if (! empty($carreras)): ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
            <?php foreach ($carreras as $c): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0"><?= esc($c['nombre']) ?></h5>
                                <span class="badge bg-primary-subtle text-primary fw-semibold">
                                    <?= esc($c['codigo']) ?>
                                </span>
                            </div>
                            <p class="text-muted flex-grow-1 mb-3">
                                <?= esc($c['descripcion'] ?? 'Descripcion pendiente, pronto tendremos mas informacion.') ?>
                            </p>
                            <div class="text-secondary small">
                                <i class="bi bi-clock-history me-1"></i>Plan <?= esc($c['codigo']) ?>
                            </div>
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

    <div class="text-center mt-4">
        <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary">
            Volver al inicio
        </a>
    </div>
</div>

<?= $this->endSection() ?>
