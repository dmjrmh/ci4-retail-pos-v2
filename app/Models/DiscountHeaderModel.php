<?php

namespace App\Models;

use CodeIgniter\Model;

class DiscountHeaderModel extends Model
{
  protected $table            = 'discountheaders';
  protected $primaryKey       = 'NoTrans';
  protected $returnType       = 'array';
  protected $allowedFields    = [
    'NoTrans', 'TglTrans', 'Ketentuan', 'TglAwal', 'TglAkhir',
    'Minimum', 'Status', 'exclude_promo', 'berlaku'
  ];
  protected $useTimestamps = false;
}
