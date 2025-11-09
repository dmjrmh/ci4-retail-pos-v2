<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0">Master Barang</h3>
    <div class="d-flex ml-auto" style="gap:.5rem">
      <form method="get" action="<?= site_url('masterbarang') ?>" class="form-inline">
        <input type="text" name="query" value="<?= esc($query ?? '') ?>" class="form-control form-control-sm" placeholder="Cari PCode/Nama...">
      </form>
      <a href="<?= site_url('masterbarang/create') ?>" class="btn btn-primary btn-sm">+ Tambah Barang</a>
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
          <th class="text-center" width="120">PCode</th>
          <th>Nama</th>
          <th class="text-center" width="90">Satuan</th>
          <th class="text-right" width="140">Harga Jual</th>
          <th class="text-right" width="140">Harga Beli</th>
          <th class="text-center" width="80">Status</th>
          <th class="text-center" width="150">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (! empty($products)): foreach ($products as $product): ?>
            <tr>
              <td class="text-monospace"><?= esc($product['PCode']) ?></td>
              <td>
                <div class="font-weight-bold"><?= esc($product['NamaLengkap']) ?></div>
                <small class="text-muted"><?= esc($product['NamaStruk']) ?></small>
              </td>
              <td class="text-center"><?= esc($product['SatuanSt']) ?></td>
              <td class="text-right"><?= number_format((float)$product['Harga1c'], 2) ?></td>
              <td class="text-right"><?= number_format((float)$product['Harga1b'], 2) ?></td>
              <td class="text-center">
                <span class="badge <?= ($product['Status'] ?? 'T') === 'T' ? 'badge-success' : 'badge-secondary' ?>">
                  <?= ($product['Status'] ?? 'T') === 'T' ? 'Aktif' : 'Nonaktif' ?>
                </span>
              </td>
              <td class="text-center">
                <a href="<?= site_url('masterbarang/edit/' . rawurlencode($product['PCode'])) ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="<?= site_url('masterbarang/delete/' . rawurlencode($product['PCode'])) ?>"
                  class="btn btn-sm btn-danger"
                  onclick="return confirm('Hapus barang <?= esc($product['PCode']) ?> ?')">Delete</a>
              </td>
            </tr>
          <?php endforeach;
        else: ?>
          <tr>
            <td colspan="7" class="text-center text-muted">Belum ada data</td>
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