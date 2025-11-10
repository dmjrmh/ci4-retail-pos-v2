<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header align-items-center justify-content-between">
    <h3 class="card-title m-0"><?= esc($title ?? 'Tambah Barang') ?></h3>
    <div class="card-tools">
      <a href="<?= site_url('masterbarang') ?>" class="btn btn-secondary btn-sm">Back</a>
    </div>
  </div>

  <div class="card-body">
    <?php if ($errors = session()->get('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul>
      </div>
    <?php endif; ?>

    <form action="<?= site_url('masterbarang/store') ?>" method="post">
      <?= csrf_field() ?>
      <?php $item = null; ?>
      <?= $this->include('masterbarang/_form') ?>
      <button type="submit" class="btn btn-primary">Save</button>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
