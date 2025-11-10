<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0">Daftar Transaksi</h3>
    <div class="ml-auto">
      <a class="btn btn-primary btn-sm" href="<?= site_url('transactions/create') ?>">Transaksi Baru</a>
    </div>
  </div>
  <div class="card-body">
    <?php if ($message = session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc($message) ?></div>
    <?php endif ?>
    <?php if ($errors = session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul>
      </div>
    <?php endif ?>

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
      </div>
      <div class="form-row">
        <div class="form-group mr-2">
          <label class="mr-2" for="date_from">Dari</label>
          <input type="date" name="date_from" id="date_from" class="form-control" value="<?= esc($filters['date_from'] ?? '') ?>">
        </div>
        <div class="form-group mr-2">
          <label class="mr-2" for="date_to">Sampai</label>
          <input type="date" name="date_to" id="date_to" class="form-control" value="<?= esc($filters['date_to'] ?? '') ?>">
        </div>
        <div class="form-group mr-2">
          <input type="text" name="query" class="form-control" placeholder="Cari NoStruk / Kasir" value="<?= esc($filters['query'] ?? '') ?>">
        </div>
      </div>
      <button class="btn btn-secondary">Filter</button>
    </form>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>NoStruk</th>
          <th>Tanggal</th>
          <th>Waktu</th>
          <th>Outlet</th>
          <th>Kasir</th>
          <th class="text-right">Total Item</th>
          <th class="text-right">Subtotal</th>
          <th class="text-right">Diskon</th>
          <th class="text-right">Total Bayar</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $__sum_item = 0;
        $__sum_subtotal = 0.0;
        $__sum_disc = 0.0;
        $__sum_total = 0.0;
        ?>
        <?php if (!empty($transactions)): foreach ($transactions as $t): ?>
            <tr>
              <td class="text-monospace"><a href="<?= site_url('transactions/show/' . $t['id']) ?>"><?= esc($t['NoStruk']) ?></a></td>
              <td><?= esc($t['Tanggal']) ?></td>
              <td><?= esc($t['Waktu']) ?></td>
              <td><?= esc($t['store_name'] ?? '') ?></td>
              <td><?= esc($t['Kasir'] ?? '') ?></td>
              <td class="text-right"><?= number_format((int)($t['TotalItem'] ?? 0), 0) ?></td>
              <td class="text-right"><?= number_format((float)($t['Subtotal'] ?? 0), 2) ?></td>
              <td class="text-right text-danger"><?= number_format((float)($t['TotalDiskon'] ?? 0), 2) ?></td>
              <td class="text-right font-weight-bold"><?= number_format((float)($t['TotalBayar'] ?? 0), 2) ?></td>
            </tr>
            <?php
            $__sum_item     += (int)($t['TotalItem'] ?? 0);
            $__sum_subtotal += (float)($t['Subtotal'] ?? 0);
            $__sum_disc     += (float)($t['TotalDiskon'] ?? 0);
            $__sum_total    += (float)($t['TotalBayar'] ?? 0);
            ?>
          <?php endforeach;
        else: ?>
          <tr>
            <td colspan="9" class="text-center text-muted">Belum ada transaksi</td>
          </tr>
        <?php endif; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="5" class="text-right"><strong>Total</strong></td>
          <td class="text-right"><strong><?= number_format($__sum_item, 0) ?></strong></td>
          <td class="text-right"><strong><?= number_format($__sum_subtotal, 2) ?></strong></td>
          <td class="text-right text-danger"><strong><?= number_format($__sum_disc, 2) ?></strong></td>
          <td class="text-right"><strong><?= number_format($__sum_total, 2) ?></strong></td>
        </tr>
      </tfoot>
    </table>
    <?php if (isset($pager)) echo $pager->links(); ?>
  </div>
</div>

<?= $this->endSection() ?>