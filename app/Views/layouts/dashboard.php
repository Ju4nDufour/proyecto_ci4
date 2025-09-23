<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<h1 class="h3 mb-4">Dashboard</h1>
<div class="row g-3">
  <?php
    // Si aún no conectaste models/controladores, mostramos 0 por defecto
    $kpis = [
      ['label'=>'Alumnos', 'value'=>$alumnosCount ?? 0],
      ['label'=>'Carreras', 'value'=>$carrerasCount ?? 0],
      ['label'=>'Cursos', 'value'=>$cursosCount ?? 0],
      ['label'=>'Profesores', 'value'=>$profesoresCount ?? 0],
      ['label'=>'Inscripciones', 'value'=>$inscripcionesCount ?? 0],
    ];
  ?>
  <?php foreach ($kpis as $k): ?>
    <div class="col-6 col-md-4 col-lg-3">
      <div class="card shadow-sm border-0 card-kpi">
        <div class="card-body text-center">
          <div class="display-6 fw-bold"><?= esc($k['value']) ?></div>
          <div class="text-muted"><?= esc($k['label']) ?></div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?= $this->endSection() ?>
