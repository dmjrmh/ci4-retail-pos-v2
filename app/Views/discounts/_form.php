<?php
$errors = session()->get('errors') ?? [];
function is_invalid($field, $errors)
{
  return isset($errors[$field]) ? 'is-invalid' : '';
}

$discount = $discount ?? [];
$stores   = $stores ?? [];
?>
<div class="form-row">
  <div class="form-group col-md-6">
    <label for="store_id">Outlet</label>
    <select id="store_id" name="store_id" class="form-control">
      <option value="">Global (semua outlet)</option>
      <?php foreach ($stores as $store): ?>
        <option value="<?= $store['id'] ?>" <?= old('store_id', $discount['store_id'] ?? '') == $store['id'] ? 'selected' : '' ?>>
          <?= esc($store['store_code'] . ' - ' . $store['name'] . ' - ' . $store['city']) ?>
        </option>
      <?php endforeach ?>
    </select>
  </div>

  <div class="form-group col-md-6">
    <label for="code">Kode Diskon</label>
    <input type="text" id="code" name="code" class="form-control <?= is_invalid('code', $errors) ?>"
      value="<?= esc(old('code', $discount['code'] ?? '')) ?>" autofocus>
    <div class="invalid-feedback"><?= esc($errors['code'] ?? '') ?></div>
  </div>
</div>

<div class="form-row">
  <div class="form-group col-md-6">
    <label for="name">Nama</label>
    <input type="text" id="name" name="name" class="form-control  <?= is_invalid('name', $errors) ?>"
      value="<?= esc(old('name', $discount['name'] ?? '')) ?>">
    <div class="invalid-feedback"><?= esc($errors['name'] ?? '') ?></div>
  </div>

  <div class="form-group col-md-3">
    <label for="start_date">Start Date</label>
    <input type="date" id="start_date" name="start_date" class="form-control  <?= is_invalid('start_date', $errors) ?>"
      value="<?= esc(old('start_date', $discount['start_date'] ?? '')) ?>">
    <div class="invalid-feedback"><?= esc($errors['start_date'] ?? '') ?></div>
  </div>

  <div class="form-group col-md-3">
    <label for="end_date">End Date</label>
    <input type="date" id="end_date" name="end_date" class="form-control  <?= is_invalid('end_date', $errors) ?>"
      value="<?= esc(old('end_date', $discount['end_date'] ?? '')) ?>">
    <div class="invalid-feedback"><?= esc($errors['end_date'] ?? '') ?></div>
  </div>
</div>

<div class="form-row">
  <div class="form-group col-md-3">
    <label for="start_time">Start Time</label>
    <input type="time" id="start_time" name="start_time" class="form-control"
      value="<?= esc(old('start_time', $discount['start_time'] ?? '')) ?>">
  </div>

  <div class="form-group col-md-3">
    <label for="end_time">End Time</label>
    <input type="time" id="end_time" name="end_time" class="form-control"
      value="<?= esc(old('end_time', $discount['end_time'] ?? '')) ?>">
  </div>
</div>

<div class="form-row">
  <div class="form-group col-md-3">
    <label for="min_amount">Minimal Belanja</label>
    <input type="number" step="1" id="min_amount" name="min_amount" class="form-control text-right"
      value="<?= esc(old('min_amount', $discount['min_amount'] ?? '0')) ?>">
  </div>

  <div class="form-group col-md-3">
    <label for="is_active">Status</label>
    <select id="is_active" name="is_active" class="form-control">
      <?php $active = (string)old('is_active', (string)($discount['is_active'] ?? '1')); ?>
      <option value="1" <?= $active === '1' ? 'selected' : '' ?>>Aktif</option>
      <option value="0" <?= $active === '0' ? 'selected' : '' ?>>Nonaktif</option>
    </select>
  </div>
</div>