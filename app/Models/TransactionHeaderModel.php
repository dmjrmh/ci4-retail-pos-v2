<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionHeaderModel extends Model
{
  protected $table            = 'transaction_headers';
  protected $primaryKey       = 'id';
  protected $returnType       = 'array';
  protected $useSoftDeletes   = true;
  protected $useTimestamps    = true;
  protected $allowedFields    = [
    'store_id', 'NoKassa', 'NoStruk',
    'Tanggal', 'Waktu', 'Kasir',
    'TotalItem', 'Subtotal',
    'TotalDiskon', 'TotalBayar',
    'Kembali'
  ];
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';
}
