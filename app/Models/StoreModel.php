<?php

namespace App\Models;

use CodeIgniter\Model;

class StoreModel extends Model
{
  protected $table = 'stores';
  protected $primaryKey = 'id';
  protected $allowedFields = ['store_code', 'name', 'address', 'city'];
  protected $useSoftDeletes = true;
  protected $useTimestamps = true;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';
}
