<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header align-items-center justify-content-between">
    <h3 class="card-title m-0"><?= esc($title ?? 'Edit Barang') ?></h3>
    <div class="card-tools">
      <a href="<?= site_url('masterbarang') ?>" class="btn btn-secondary btn-sm ml-2">Back</a>
    </div>
  </div>

  <div class="card-body">
    <?php if ($errors = session()->get('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul>
      </div>
    <?php endif; ?>

    <form action="<?= site_url('masterbarang/update/'.rawurlencode($item['PCode'])) ?>" method="post">
      <?= csrf_field() ?>
      <?= $this->include('masterbarang/_form') ?>
      <div class="d-flex align-items-center" style="gap:.5rem">
        <button type="submit" class="btn btn-primary">Update</button>
        <a class="btn btn-light" href="<?= site_url('masterbarang') ?>">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
