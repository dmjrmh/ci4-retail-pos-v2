<?php

namespace App\Models;

use CodeIgniter\Model;

class DiscountDetailModel extends Model
{
  protected $table            = 'discount_details';
  protected $primaryKey       = 'id';
  protected $returnType       = 'array';
  protected $useSoftDeletes   = true;
  protected $useTimestamps = true;
  protected $allowedFields    = [
    'discount_id', 'PCode', 'type', 'value',
  ];
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';
}
