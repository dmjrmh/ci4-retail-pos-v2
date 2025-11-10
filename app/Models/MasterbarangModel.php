<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterbarangModel extends Model
{
  protected $table            = 'masterbarang';
  protected $primaryKey       = 'id';
  protected $returnType       = 'array';
  protected $useSoftDeletes   = true;
  protected $useTimestamps = true;
  protected $allowedFields = [
    'PCode', 'NamaLengkap', 'NamaStruk', 'SatuanSt',
    'Harga1c', 'Harga1b', 'Barcode1', 'Status',
    'created_at', 'updated_at'
  ];
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';
}
