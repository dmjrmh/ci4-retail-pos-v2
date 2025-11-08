<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiDetailModel extends Model
{
  protected $table            = 'transaksi_detail';
  protected $returnType       = 'array';
  protected $allowedFields = [
    'NoKassa', 'Gudang', 'NoStruk', 'Tanggal', 'Waktu', 'Kasir',
    'KdStore', 'PCode', 'Qty', 'Berat', 'Harga',
    'Ketentuan1', 'Disc1', 'Jenis1',
    'Ketentuan2', 'Disc2', 'Jenis2',
    'Ketentuan3', 'Disc3', 'Jenis3',
    'Ketentuan4', 'Disc4', 'Jenis4',
    'Netto', 'Hpp', 'Status', 'Keterangan', 'Service_charge',
    'Komisi', 'PPN', 'Printer', 'KdMeja', 'KdAgent'
  ];

}
