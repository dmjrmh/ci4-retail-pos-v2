<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title m-0">Transaksi Baru</h3>
    <div class="ml-auto">
      <a href="<?= site_url('transactions') ?>" class="btn btn-secondary btn-sm">Back</a>
    </div>
  </div>
  <div class="card-body">
    <div id="promo-box-top" class="mb-3"></div>
    <?php if ($message = session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc($message) ?></div>
    <?php endif ?>
    <?php if ($errors = session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul>
      </div>
    <?php endif ?>

    <form action="<?= site_url('transactions/store') ?>" method="post" id="frm-pos">
      <?= csrf_field() ?>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="store_id">Outlet</label>
          <select name="store_id" id="store_id" class="form-control" required>
            <?php foreach (($stores ?? []) as $s): ?>
              <option value="<?= $s['id'] ?>"><?= esc(($s['store_code'] ?? '') . ' - ' . ($s['name'] ?? '')) ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="form-group col-md-4">
          <label for="Kasir">Kasir</label>
          <input type="text" class="form-control" id="Kasir" name="Kasir" placeholder="Nama kasir" value="<?= esc(old('Kasir', '')) ?>">
        </div>
        <div class="form-group col-md-2">
          <label for="NoKassa">No Kassa</label>
          <input type="text" class="form-control" id="NoKassa" name="NoKassa" maxlength="3" placeholder="001" value="<?= esc(old('NoKassa', '')) ?>">
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered" id="tbl-cart">
          <thead>
            <tr>
              <th style="width: 320px">Barang</th>
              <th>Nama Barang</th>
              <th class="text-right" style="width: 150px">Harga</th>
              <th style="width: 120px">Qty</th>
              <th style="width: 60px"></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <div class="form-row mt-3">
        <div class="form-group col-md-3">
          <label for="Bayar">Jumlah Bayar</label>
          <input type="number" step="0.01" class="form-control" id="Bayar" name="Bayar" placeholder="0">
        </div>
      </div>

      <button type="button" class="btn btn-sm btn-secondary" id="btn-add">Tambah Barang</button>
      <button type="submit" class="btn btn-sm btn-primary">Simpan Transaksi</button>

      <div class="mt-3">
        <div class="row">
          <div class="col-md-3"><strong>Subtotal (sebelum diskon)</strong></div>
          <div class="col-md-3 text-right" id="sum-subtotal">0.00</div>
        </div>
        <div class="row">
          <div class="col-md-3"><strong>Total Diskon</strong></div>
          <div class="col-md-3 text-right text-danger" id="sum-disc">0.00</div>
        </div>
        <div class="row">
          <div class="col-md-3"><strong>Total Bayar</strong></div>
          <div class="col-md-3 text-right font-weight-bold" id="sum-total">0.00</div>
        </div>
        <div class="row">
          <div class="col-md-3"><strong>Kembali</strong></div>
          <div class="col-md-3 text-right" id="sum-kembali">0.00</div>
        </div>
      </div>
    </form>
  </div>
</div>

<template id="row-template">
  <tr>
    <td>
      <select name="pcode[]" class="form-control form-control-sm pcode-select">
        <option value="">-- Pilih Barang --</option>
        <?php foreach (($items ?? []) as $it): ?>
          <option value="<?= $it['PCode'] ?>" data-nama="<?= esc($it['NamaStruk'] ?? $it['NamaLengkap']) ?>" data-harga="<?= (float)($it['Harga1c'] ?? 0) ?>">
            <?= esc($it['PCode'] . ' - ' . ($it['NamaStruk'] ?? $it['NamaLengkap'])) ?>
          </option>
        <?php endforeach ?>
      </select>
    </td>
    <td class="nama align-middle text-muted">-<div class="disc-hint text-muted small"></div>
    </td>
    <td class="harga text-right align-middle">0.00</td>
    <td><input name="qty[]" type="number" min="1" value="1" class="form-control form-control-sm qty"></td>
    <td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del">x</button></td>
  </tr>
</template>

<script>
  (function() {
    const tbody = document.querySelector('#tbl-cart tbody');
    const tpl = document.querySelector('#row-template');
    const addBtn = document.querySelector('#btn-add');
    const promoBox = document.querySelector('#promo-box-top') || document.createElement('div');

    function addRow(pcode = '') {
      const node = tpl.content.cloneNode(true);
      tbody.appendChild(node);
      const row = tbody.lastElementChild;
      bindRow(row);
      if (pcode) {
        const sel = row.querySelector('.pcode-select');
        sel.value = pcode;
        reflectSelection(row, sel);
      }
    }

    function bindRow(row) {
      row.querySelector('.btn-del').addEventListener('click', () => {
        row.remove();
        previewTotals();
      });
      row.querySelector('.pcode-select').addEventListener('change', (e) => {
        reflectSelection(row, e.target);
        previewTotals();
      });
      row.querySelector('.qty').addEventListener('input', previewTotals);
    }

    function reflectSelection(row, selectEl) {
      const opt = selectEl.options[selectEl.selectedIndex];
      const nama = opt ? (opt.getAttribute('data-nama') || '-') : '-';
      const harga = opt ? Number(opt.getAttribute('data-harga') || 0) : 0;
      row.querySelector('.nama').textContent = nama || '-';
      row.querySelector('.harga').textContent = harga.toFixed(2);
      const sid = document.getElementById('store_id').value;
      const pcode = selectEl.value;
      const hintEl = row.querySelector('.disc-hint');
      if (!sid || !pcode) {
        if (hintEl) hintEl.textContent = '';
        row._promos = [];
        previewTotals();
        return;
      }
      fetch('<?= site_url('api/discounts/active') ?>?store_id=' + encodeURIComponent(sid) + '&pcode=' + encodeURIComponent(pcode))
        .then(r => r.ok ? r.json() : [])
        .then(list => {
          row._promos = Array.isArray(list) ? list : [];
          if (!row._promos.length) {
            if (hintEl) hintEl.textContent = '';
            previewTotals();
            return;
          }
          const d = row._promos[0];
          const tipe = d.type === 'P' ? (Number(d.value || 0).toFixed(2) + '%') : ('Rp' + Number(d.value || 0).toLocaleString('id-ID'));
          hintEl.textContent = d.code + ' • ' + tipe + ' • min Rp' + Number(d.min_amount || 0).toLocaleString('id-ID');
          previewTotals();
        })
        .catch(() => {
          if (hintEl) hintEl.textContent = '';
          row._promos = [];
          previewTotals();
        });
    }

    addBtn.addEventListener('click', () => addRow());

    async function loadPromosDetailed() {
      const sid = document.getElementById('store_id').value;
      if (!sid) {
        promoBox.innerHTML = '';
        return;
      }
      try {
        const res = await fetch('<?= site_url('api/discounts/active') ?>?store_id=' + encodeURIComponent(sid));
        if (!res.ok) throw new Error('err');
        const rows = await res.json();
        if (!rows || !rows.length) {
          promoBox.innerHTML = '<div class="alert alert-secondary mb-0">Tidak ada promo aktif untuk outlet terpilih.</div>';
          return;
        }
        let html = '<div class="alert alert-info mb-0"><div class="font-weight-bold mb-2">Promo Aktif</div>';
        for (const r of rows) {
          const jam = (r.start_time ? r.start_time.substring(0, 5) : '--') + ' sampai ' + (r.end_time ? r.end_time.substring(0, 5) : '--');
          html += `<div class=\"mb-2\"><div><span class=\"text-monospace\">${r.code}</span> - ${r.name} (tgl ${r.start_date} s/d ${r.end_date}, mulai jam ${jam}, min belanja Rp ${Number(r.min_amount||0).toLocaleString('id-ID')})</div>`;
          if (Array.isArray(r.items) && r.items.length) {
            html += '<ul class="mb-0">';
            for (const it of r.items) {
              const tipe = it.type === 'P' ? (Number(it.value || 0).toFixed(2) + '%') : ('Rp ' + Number(it.value || 0).toLocaleString('id-ID'));
              html += `<li><span class=\"text-monospace\">${it.PCode}</span> - ${it.Nama || ''}: diskon ${tipe}</li>`;
            }
            html += '</ul>';
          }
          html += '</div>';
        }
        html += '</div>';
        promoBox.innerHTML = html;
      } catch (e) {
        promoBox.innerHTML = '';
      }
    }

    async function loadPromos() {
      const sid = document.getElementById('store_id').value;
      if (!sid) {
        promoBox.innerHTML = '';
        return;
      }
      try {
        const res = await fetch('<?= site_url('api/discounts/active') ?>?store_id=' + encodeURIComponent(sid));
        if (!res.ok) throw new Error('err');
        const rows = await res.json();
        if (!rows || !rows.length) {
          promoBox.innerHTML = '<div class="text-muted">Tidak ada promo aktif.</div>';
          return;
        }
        let html = '<div class="alert alert-info mb-0"><div class="font-weight-bold mb-2">Promo Aktif</div><ul class="mb-0">';
        for (const r of rows) {
          const jam = (r.start_time ? r.start_time.substring(0, 5) : '--') + ' - ' + (r.end_time ? r.end_time.substring(0, 5) : '--');
          html += `<li><span class="text-monospace">${r.code}</span> - ${r.name} (tgl ${r.start_date} s/d ${r.end_date}, jam ${jam}, min Rp${Number(r.min_amount||0).toLocaleString('id-ID')})</li>`;
        }
        html += '</ul></div>';
        promoBox.innerHTML = html;
      } catch (e) {
        promoBox.innerHTML = '';
      }
    }
    document.getElementById('store_id').addEventListener('change', () => {
      if (typeof loadPromosDetailed === 'function') {
        loadPromosDetailed();
      } else {
        loadPromos();
      }
      previewTotals();
    });
    if (typeof loadPromosDetailed === 'function') {
      loadPromosDetailed();
    } else {
      loadPromos();
    }

    addRow();

    function collectCart() {
      const rows = Array.from(tbody.querySelectorAll('tr'));
      const pcodes = [];
      const qtys = [];
      for (const r of rows) {
        const p = r.querySelector('.pcode-select')?.value || '';
        const q = parseInt(r.querySelector('.qty')?.value || '0', 10);
        if (p && q > 0) {
          pcodes.push(p);
          qtys.push(q);
        }
      }
      return {
        pcodes,
        qtys
      };
    }

    async function previewTotals() {
      const sid = document.getElementById('store_id').value;
      const {
        pcodes,
        qtys
      } = collectCart();
      if (!sid || pcodes.length === 0) {
        updateTotals(0, 0, 0);
        return;
      }
      const form = new URLSearchParams();
      form.append('store_id', sid);
      pcodes.forEach(p => form.append('pcode[]', p));
      qtys.forEach(q => form.append('qty[]', q));
      try {
        const res = await fetch('<?= site_url('transactions/preview') ?>', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: form
        });
        if (!res.ok) {
          updateTotals(0, 0, 0);
          return;
        }
        const data = await res.json();
        updateTotals(Number(data.total_before || 0), Number(data.total_disc || 0), Number(data.total_bayar || 0));
      } catch (e) {
        updateTotals(0, 0, 0);
      }
    }

    function updateTotals(subtotal, disc, total) {
      const bayar = Number(document.getElementById('Bayar').value || 0);
      const kembali = Math.max(0, bayar - total);
      const fmt = (n) => n.toLocaleString('id-ID', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
      document.getElementById('sum-subtotal').textContent = fmt(subtotal);
      document.getElementById('sum-disc').textContent = fmt(disc);
      document.getElementById('sum-total').textContent = fmt(total);
      document.getElementById('sum-kembali').textContent = fmt(kembali);
    }

    document.getElementById('Bayar').addEventListener('input', previewTotals);
    document.getElementById('tbl-cart').addEventListener('input', previewTotals);
    document.getElementById('tbl-cart').addEventListener('change', previewTotals);
    previewTotals();
  })();
</script>

<?= $this->endSection() ?>