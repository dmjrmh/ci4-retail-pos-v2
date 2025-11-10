<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0">Detail Transaksi</h3>
    <div class="ml-auto">
      <a class="btn btn-secondary btn-sm" href="<?= site_url('transactions') ?>">Kembali</a>
    </div>
  </div>
  <div class="card-body">
    <div class="mb-3">
      <div><strong>NoStruk:</strong> <span class="text-monospace"><?= esc($trx['NoStruk']) ?></span></div>
      <div><strong>Tanggal:</strong> <?= esc($trx['Tanggal']) ?> &nbsp; <strong>Waktu:</strong> <?= esc($trx['Waktu']) ?></div>
      <div><strong>Outlet:</strong> <?= esc(($trx['store_code'] ?? '') . ' - ' . ($trx['store_name'] ?? '')) ?></div>
      <div><strong>Kasir:</strong> <?= esc($trx['Kasir'] ?? '-') ?></div>
    </div>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>PCode</th>
          <th>Nama Barang</th>
          <th class="text-right">Harga</th>
          <th class="text-right">Qty</th>
          <th class="text-right">Diskon</th>
          <th class="text-right">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (($items ?? []) as $it): ?>
          <tr>
            <td class="text-monospace"><?= esc($it['PCode']) ?></td>
            <td><?= esc($it['NamaStruk'] ?? $it['NamaLengkap'] ?? '') ?></td>
            <td class="text-right"><?= number_format((float)($it['Harga'] ?? 0), 2) ?></td>
            <td class="text-right"><?= number_format((int)($it['Qty'] ?? 0), 0) ?></td>
            <td class="text-right text-danger"><?= number_format((float)($it['Disc'] ?? 0), 2) ?></td>
            <td class="text-right font-weight-bold"><?= number_format((float)($it['Subtotal'] ?? 0), 2) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="row mt-3">
      <div class="col-md-3"><strong>Total Item</strong></div>
      <div class="col-md-3 text-right"><?= number_format((int)($trx['TotalItem'] ?? 0), 0) ?></div>
    </div>
    <div class="row">
      <div class="col-md-3"><strong>Subtotal</strong></div>
      <div class="col-md-3 text-right"><?= number_format((float)($trx['Subtotal'] ?? 0), 2) ?></div>
    </div>
    <div class="row">
      <div class="col-md-3"><strong>Total Diskon</strong></div>
      <div class="col-md-3 text-right text-danger"><?= number_format((float)($trx['TotalDiskon'] ?? 0), 2) ?></div>
    </div>
    <div class="row">
      <div class="col-md-3"><strong>Total Bayar</strong></div>
      <div class="col-md-3 text-right font-weight-bold"><?= number_format((float)($trx['TotalBayar'] ?? 0), 2) ?></div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>