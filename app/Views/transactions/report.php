<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0">Laporan Penjualan</h3>
    <div class="ml-auto">
      <a class="btn btn-secondary btn-sm" href="<?= site_url('transactions') ?>">Daftar Transaksi</a>
    </div>
  </div>
  <div class="card-body">
    <form method="get" class="form-inline mb-3">
      <div class="form-row mb-2">
        <div class="form-group mr-2">
          <label class="mr-2" for="store_id">Outlet</label>
          <select name="store_id" id="store_id" class="form-control">
            <option value="">Semua</option>
            <?php foreach (($stores ?? []) as $s): ?>
              <option value="<?= $s['id'] ?>" <?= (($filters['store_id'] ?? '') == $s['id']) ? 'selected' : '' ?>>
                <?= esc(($s['store_code'] ?? '') . ' - ' . ($s['name'] ?? '')) ?>
              </option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="form-group mr-2">
          <label class="mr-2" for="date_from">Dari</label>
          <input type="date" name="date_from" id="date_from" class="form-control" value="<?= esc($filters['date_from'] ?? '') ?>">
        </div>
        <div class="form-group mr-2">
          <label class="mr-2" for="date_to">Sampai</label>
          <input type="date" name="date_to" id="date_to" class="form-control" value="<?= esc($filters['date_to'] ?? '') ?>">
        </div>
        <button class="btn btn-primary">Terapkan</button>
      </div>
    </form>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Outlet</th>
          <th class="text-right">Jumlah Transaksi</th>
          <th class="text-right">Total Item</th>
          <th class="text-right">Subtotal</th>
          <th class="text-right">Diskon</th>
          <th class="text-right">Total Bayar</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $__grand_trx = 0;
        $__grand_item = 0;
        $__grand_subtotal = 0.0;
        $__grand_disc = 0.0;
        $__grand_total = 0.0;
        ?>
        <?php if (!empty($summary)): foreach ($summary as $row): ?>
            <tr>
              <td><?= esc(($row['store_code'] ?? '') . ' - ' . ($row['store_name'] ?? '')) ?></td>
              <td class="text-right"><?= number_format((int)($row['trx_count'] ?? 0), 0) ?></td>
              <td class="text-right"><?= number_format((int)($row['total_item'] ?? 0), 0) ?></td>
              <td class="text-right"><?= number_format((float)($row['subtotal'] ?? 0), 2) ?></td>
              <td class="text-right text-danger"><?= number_format((float)($row['total_diskon'] ?? 0), 2) ?></td>
              <td class="text-right font-weight-bold"><?= number_format((float)($row['total_bayar'] ?? 0), 2) ?></td>
            </tr>
            <?php
            $__grand_trx     += (int)($row['trx_count'] ?? 0);
            $__grand_item    += (int)($row['total_item'] ?? 0);
            $__grand_subtotal += (float)($row['subtotal'] ?? 0);
            $__grand_disc    += (float)($row['total_diskon'] ?? 0);
            $__grand_total   += (float)($row['total_bayar'] ?? 0);
            ?>
          <?php endforeach;
        else: ?>
          <tr>
            <td colspan="6" class="text-center text-muted">Tidak ada data</td>
          </tr>
        <?php endif; ?>
      </tbody>
      <tfoot>
        <tr>
          <td class="text-right"><strong>Grand Total</strong></td>
          <td class="text-right"><strong><?= number_format($__grand_trx, 0) ?></strong></td>
          <td class="text-right"><strong><?= number_format($__grand_item, 0) ?></strong></td>
          <td class="text-right"><strong><?= number_format($__grand_subtotal, 2) ?></strong></td>
          <td class="text-right text-danger"><strong><?= number_format($__grand_disc, 2) ?></strong></td>
          <td class="text-right"><strong><?= number_format($__grand_total, 2) ?></strong></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

<?= $this->endSection() ?>