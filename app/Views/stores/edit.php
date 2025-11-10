<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header align-items-center justify-content-between">
    <h3 class="card-title m-0"><?= esc($title ?? 'Edit Outlet') ?></h3>
    <div class="card-tools">
      <a href="<?= site_url('stores') ?>" class="btn btn-secondary btn-sm">Back</a>
    </div>
  </div>

  <div class="card-body">
    <?php if ($errors = session()->get('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul>
      </div>
    <?php endif; ?>

    <form action="<?= site_url('stores/update/' . $outlet['id']) ?>" method="post">
      <?= csrf_field() ?>
      <?= $this->include('stores/_form') ?>
      <button type="submit" class="btn btn-primary">Update</button>
    </form>
  </div>
</div>

<?= $this->endSection() ?>