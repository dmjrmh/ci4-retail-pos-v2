<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="<?= base_url() ?>" class="brand-link text-center">
    <span class="brand-text font-weight-light">Retail POS</span>
  </a>

  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
        <li class="nav-item">
          <a href="<?= base_url('masterbarang') ?>" class="nav-link <?= is_active('masterbarang') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-boxes"></i>
            <p>Master Barang</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('transaksi') ?>" class="nav-link <?= is_active('transaksi') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-cash-register"></i>
            <p>Transaksi</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('discount') ?>" class="nav-link <?= is_active('discount') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-tags"></i>
            <p>Discount</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>