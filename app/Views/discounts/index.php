<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0">Data Diskon</h3>
    <div class="d-flex ml-auto" style="gap:.5rem">
      <form method="get" action="<?= site_url('discounts') ?>" class="form-inline">
        <input type="text" name="query" value="<?= esc($query ?? '') ?>" class="form-control form-control-sm" placeholder="Cari Kode / Nama">
      </form>
      <a href="<?= site_url('discounts/create') ?>" class="btn btn-primary btn-sm">+ Tambah Diskon</a>
    </div>
  </div>

  <div class="card-body">
    <?php if ($message = session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc($message) ?></div>
    <?php endif ?>
    <?php if ($errors = session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach ?>
        </ul>
      </div>
    <?php endif ?>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th class="text-center">Kode</th>
          <th class="text-center">Nama</th>
          <th class="text-center">Outlet</th>
          <th class="text-center">Periode</th>
          <th class="text-center">Aktif</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (! empty($discounts)): foreach ($discounts as $d): ?>
            <tr>
              <td class="text-monospace"><?= esc($d['code']) ?></td>
              <td><?= esc($d['name']) ?></td>
              <td><?= esc($d['store_name'] ?? 'Global') ?></td>
              <td><?= esc($d['start_date']) ?> s/d <?= esc($d['end_date']) ?></td>
              <td class="text-center">
                <span class="badge <?= ((int)($d['is_active'] ?? 0)) === 1 ? 'badge-success' : 'badge-secondary' ?>">
                  <?= ((int)($d['is_active'] ?? 0)) === 1 ? 'Aktif' : 'Nonaktif' ?>
                </span>
              </td>
              <td class="text-center">
                <a href="<?= site_url('discounts/edit/' . ($d['id'])) ?>" class="btn btn-sm btn-warning">Edit</a>
                <form action="<?= site_url('discounts/delete/' . $d['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus diskon <?= esc($d['code']) ?> ?')">
                  <?= csrf_field() ?>
                  <input type="hidden" name="_method" value="DELETE">
                  <button class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach;
        else: ?>
          <tr>
            <td colspan="6" class="text-center text-muted">Belum ada data</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <div class="mt-3">
      <?= $pager->links() ?>
    </div>
  </div>
</div>

<?= $this->endSection() ?>