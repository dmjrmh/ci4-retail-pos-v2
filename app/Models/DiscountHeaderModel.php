<?php

namespace App\Models;

use CodeIgniter\Model;

class DiscountHeaderModel extends Model
{
  protected $table            = 'discount_headers';
  protected $primaryKey       = 'id';
  protected $returnType       = 'array';
  protected $useSoftDeletes   = true;
  protected $useTimestamps = true;
  protected $allowedFields    = [
  'store_id', 'code', 'name', 'start_date', 'end_date',
  'start_time', 'end_time', 'min_amount', 'is_active',
  ];
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';
}
