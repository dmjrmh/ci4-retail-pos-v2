<?php
$errors = session()->get('errors') ?? [];
function is_invalid($field, $errors)
{
  return isset($errors[$field]) ? 'is-invalid' : '';
}
// Normalize incoming row/item variable to a single array
$data = isset($row) ? $row : (isset($item) ? $item : []);
?>
<div class="form-row">
  <div class="form-group col-md-6">
    <label for="NamaLengkap">Nama Barang <span class="text-danger">*</span></label>
    <input type="text" id="NamaLengkap" name="NamaLengkap"
      class="form-control <?= is_invalid('NamaLengkap', $errors) ?>"
      value="<?= esc(old('NamaLengkap', $data['NamaLengkap'] ?? '')) ?>" <?= isset($data) ? '' : 'autofocus' ?>>
    <div class="invalid-feedback"><?= esc($errors['NamaLengkap'] ?? '') ?></div>
  </div>

  <div class="form-group col-md-6">
    <label for="PCode">PCode <span class="text-danger">*</span></label>
    <input type="text" id="PCode" name="PCode"
      class="form-control <?= is_invalid('PCode', $errors) ?>"
      value="<?= esc(old('PCode', $data['PCode'] ?? '')) ?>">
    <div class="invalid-feedback"><?= esc($errors['PCode'] ?? '') ?></div>
  </div>
</div>

<div class="form-row">
  <div class="form-group col-md-6">
    <label for="NamaStruk">Nama Struk</label>
    <input type="text" id="NamaStruk" name="NamaStruk" class="form-control" maxlength="30"
      placeholder="Nama singkat untuk dicetak pada struk"
      value="<?= esc(old('NamaStruk', $data['NamaStruk'] ?? '')) ?>">
  </div>

  <div class="form-group col-md-3">
    <label for="SatuanSt">Satuan</label>
    <?php $units = ['pcs' => 'PCS', 'pack' => 'PACK', 'crat' => 'CRAT'];
      $selectedUnit = strtolower(old('SatuanSt', $data['SatuanSt'] ?? 'pcs'));
    ?>
    <select id="SatuanSt" name="SatuanSt" class="form-control">
      <?php foreach ($units as $val => $label): ?>
        <option value="<?= esc($val) ?>" <?= $selectedUnit === $val ? 'selected' : '' ?>>
          <?= esc($label) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="form-group col-md-3">
    <label for="Barcode1">Barcode</label>
    <input type="text" id="Barcode1" name="Barcode1" class="form-control"
      value="<?= esc(old('Barcode1', $data['Barcode1'] ?? '')) ?>">
  </div>

</div>

<div class="form-row">
  <div class="form-group col-md-6">
    <label for="Harga1c">Harga Jual</label>
    <input type="number" step="1" id="Harga1c" name="Harga1c" class="form-control"
      value="<?= esc(old('Harga1c', $data['Harga1c'] ?? '0')) ?>">
  </div>

  <div class="form-group col-md-6">
    <label for="Harga1b">Harga Beli</label>
    <input type="number" step="1" id="Harga1b" name="Harga1b" class="form-control"
      value="<?= esc(old('Harga1b', $data['Harga1b'] ?? '0')) ?>">
  </div>
  <!-- PersenPajak dihapus karena tidak ada di migration/Model -->
</div>

<div class="form-group">
  <label for="Status">Status</label>
  <select id="Status" name="Status" class="form-control">
    <?php $val = old('Status', $data['Status'] ?? 'T'); ?>
    <option value="T" <?= $val === 'T' ? 'selected' : '' ?>>Aktif</option>
    <option value="F" <?= $val === 'F' ? 'selected' : '' ?>>Nonaktif</option>
  </select>
</div>
