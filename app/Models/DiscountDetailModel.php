<?php

namespace App\Models;

use CodeIgniter\Model;

class DiscountDetailModel extends Model
{
    protected $table            = 'discountdetail';
    protected $returnType       = 'array';
    protected $allowedFields    = [
      'NoTrans', 'PCode', 'Jenis', 'Nilai'
    ];
}
