<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DiscountDetail extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'NoTrans' => ['type' => 'VARCHAR', 'constraint' => 11, 'null' => false],
      'PCode'   => ['type' => 'CHAR', 'constraint' => 15, 'null' => false],
      'Jenis'   => ['type' => 'VARCHAR', 'constraint' => 1,  'null' => true, 'comment' => 'P = persen, R = rupiah'],
      'Nilai'   => ['type' => 'DECIMAL', 'constraint' => '10,3', 'null' => true],
    ]);

    $this->forge->addKey(['NoTrans', 'PCode'], true);
    $this->forge->addForeignKey('NoTrans', 'discountheader', 'NoTrans', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('PCode', 'masterbarang', 'PCode', 'CASCADE', 'CASCADE');
    $this->forge->createTable('discountdetail');
  }

  public function down()
  {
    $this->forge->dropTable('discountdetail');
  }
}
