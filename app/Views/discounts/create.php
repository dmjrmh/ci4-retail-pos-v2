<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0">Tambah Diskon</h3>
    <div class="ml-auto">
      <a href="<?= site_url('discounts') ?>" class="btn btn-secondary btn-sm">Back</a>
    </div>
  </div>
  <div class="card-body">
    <?php if ($errors = session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach ?>
        </ul>
      </div>
    <?php endif ?>
    <form action="<?= site_url('discounts/store') ?>" method="post">
      <?= csrf_field() ?>
      <?php $discount = null; ?>
      <?= $this->include('discounts/_form') ?>
      <button class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>

<?= $this->endSection() ?>