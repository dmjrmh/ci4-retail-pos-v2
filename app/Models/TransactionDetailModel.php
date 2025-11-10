<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionDetailModel extends Model
{
  protected $table            = 'transaction_details';
  protected $primaryKey       = 'id';
  protected $returnType       = 'array';
  protected $useSoftDeletes   = true;
  protected $useTimestamps    = true;
  protected $allowedFields    = ['transaction_id', 'PCode', 'Qty', 'Harga', 'Disc', 'Subtotal'];
  protected $dateFormat       = 'datetime';
  protected $createdField     = 'created_at';
  protected $updatedField     = 'updated_at';
  protected $deletedField     = 'deleted_at';
}
