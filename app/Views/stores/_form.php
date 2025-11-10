<?php
$errors = session()->get('errors') ?? [];
function is_invalid($field, $errors)
{
  return isset($errors[$field]) ? 'is-invalid' : '';
}

$outlet = $outlet ?? [];
?>
<div class="form-row">
  <div class="form-group col-md-6">
    <label for="name">Nama Outlet <span class="text-danger">*</span></label>
    <input type="text" id="name" name="name"
      class="form-control <?= is_invalid('name', $errors) ?>"
      value="<?= esc(old('name', $outlet['name'] ?? '')) ?>" <?= isset($outlet) ? '' : 'autofocus' ?>>
    <div class="invalid-feedback"><?= esc($errors['name'] ?? '') ?></div>
  </div>

  <div class="form-group col-md-6">
    <label for="store_code">Kode Outlet</label>
    <input type="text" id="store_code" name="store_code"
      class="form-control "
      value="<?= esc(old('store_code', $outlet['store_code'] ?? '')) ?>">
  </div>
</div>

<div class="form-row">
  <div class="form-group col-md-6">
    <label for="address">Alamat</label>
    <textarea id="address" name="address" class="form-control" rows="3"><?= esc(old('address', $outlet['address'] ?? '')) ?></textarea>
  </div>
</div>

<div class="form-row">
  <div class="form-group col-md-6">
    <label for="city">Kota</label>
    <input type="text" id="city" name="city" class="form-control"
      value="<?= esc(old('city', $outlet['city'] ?? '')) ?>">
  </div>
</div>