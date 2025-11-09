<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0">Data Outlet</h3>
    <div class="d-flex ml-auto" style="gap:.5rem">
      <form method="get" action="<?= site_url('stores') ?>" class="form-inline">
        <input type="text" name="query" value="<?= esc($query ?? '') ?>" class="form-control form-control-sm" placeholder="Cari Kode Outlet / Nama">
      </form>
      <a href="<?= site_url('stores/create') ?>" class="btn btn-primary btn-sm">+ Tambah Outlet</a>
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
          <th class="text-center">Nama Outlet</th>
          <th class="text-center">Kode Outlet</th>
          <th class="text-center">Alamat</th>
          <th class="text-center">Kota</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (! empty($outlets)): foreach ($outlets as $outlet): ?>
            <tr>
              <td><?= esc($outlet['name']) ?></td>
              <td class="text-monospace"><?= esc($outlet['store_code']) ?></td>
              <td><?= esc($outlet['address']) ?></td>
              <td><?= esc($outlet['city']) ?></td>
              <td class="text-center">
                <a href="<?= site_url('stores/edit/' . ($outlet['id'])) ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="<?= site_url('stores/delete/' . ($outlet['id'])) ?>"
                  class="btn btn-sm btn-danger"
                  onclick="return confirm('Hapus outlet <?= esc($outlet['name']) ?> ?')">Delete</a>
              </td>
            </tr>
          <?php endforeach;
        else: ?>
          <tr>
            <td colspan="5" class="text-center text-muted">Belum ada data</td>
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