<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterbarangModel extends Model
{
  protected $table            = 'masterbarang';
  protected $primaryKey       = 'PCode';
  protected $returnType       = 'array';
  protected $useSoftDeletes   = false;
  protected $useTimestamps = false;
  protected $allowedFields = [
    'PCode', 'NamaLengkap', 'NamaStruk', 'NamaInitial', 'SatuanSt', 'SatuanBl',
    'KonvBlSt', 'Harga0b', 'Satuan1', 'Konv1st', 'Harga1c', 'Harga1t', 'Harga1b',
    'Satuan2', 'JenisBarang', 'Barcode1', 'Barcode2', 'Barcode3',
    'KdDivisi', 'KdSubDivisi', 'KdKategori', 'KdSubKategori', 'KdBrand', 'KdSubBrand',
    'Panjang', 'Lebar', 'Tinggi', 'HargaBeli', 'PersenPajak', 'Tipe', 'Status',
    'AddDate', 'EditDate', 'KdKomisi', 'Service_charge', 'JenisPajak', 'HPP',
    'StatusPajak', 'FlagReady', 'Printer', 'Jenis', 'komisi', 'DiscInternal'
  ];
}
