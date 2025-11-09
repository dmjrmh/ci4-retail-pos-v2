<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0">Edit Diskon</h3>
    <div class="ml-auto">
      <a href="<?= site_url('discounts') ?>" class="btn btn-secondary btn-sm">Back</a>
    </div>
  </div>
  <div class="card-body">
    <?php if ($message = session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc($message) ?></div>
    <?php endif ?>
    <?php if ($errors = session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach ?>
        </ul>
      </div>
    <?php endif ?>

    <form action="<?= site_url('discounts/update/' . $discount['id']) ?>" method="post" class="mb-4">
      <?= csrf_field() ?>
      <?= $this->include('discounts/_form') ?>
      <button class="btn btn-primary">Update Header</button>
    </form>

    <hr>

    <h5 class="mb-3">Tambah Produk Diskon</h5>
    <form action="<?= site_url('discounts/' . $discount['id'] . '/details/store') ?>" method="post" class="form-inline mb-3">
      <?= csrf_field() ?>
      <div class="form-group mr-2">
        <label class="mr-2" for="PCode">Kode Barang</label>
        <select id="PCode" name="PCode" class="form-control">
          <?php foreach (($items ?? []) as $it): ?>
            <option value="<?= $it['PCode'] ?>">
              <?= esc($it['PCode'] . ' - ' . ($it['NamaStruk'] ?? $it['NamaLengkap'])) ?>
            </option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="form-group mr-2">
        <label class="mr-2" for="type">Tipe</label>
        <select id="type" name="type" class="form-control">
          <option value="P">Persen</option>
          <option value="R">Rupiah</option>
        </select>
      </div>
      <div class="form-group mr-2">
        <label class="mr-2" for="value">Nilai</label>
        <input type="number" step="0.001" id="value" name="value" class="form-control text-right" value="<?= esc(old('value', '0')) ?>">
      </div>
      <button class="btn btn-success">Tambah / Update</button>
    </form>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th class="text-center">PCode</th>
          <th>Nama</th>
          <th class="text-center">Tipe</th>
          <th class="text-right">Nilai</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($details)): foreach ($details as $row): ?>
            <tr>
              <td class="text-monospace"><?= esc($row['PCode']) ?></td>
              <td><?= esc($row['NamaStruk'] ?? $row['NamaLengkap'] ?? '') ?></td>
              <td class="text-center"><?= $row['type'] === 'P' ? 'Persen' : 'Rupiah' ?></td>
              <td class="text-right"><?= number_format((float)$row['value'], 3) ?></td>
              <td class="text-center">
                <form action="<?= site_url('discounts/details/delete/' . $row['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus diskon detail <?= esc($row['PCode']) ?> ?')">
                  <?= csrf_field() ?>
                  <input type="hidden" name="_method" value="DELETE">
                  <button class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach;
        else: ?>
          <tr>
            <td colspan="5" class="text-center text-muted">Belum ada detail</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>