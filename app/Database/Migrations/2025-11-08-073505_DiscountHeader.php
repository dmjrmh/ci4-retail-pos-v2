<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DiscountHeader extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'NoTrans'       => ['type' => 'VARCHAR', 'constraint' => 11, 'null' => false],
      'TglTrans'      => ['type' => 'DATE', 'null' => true],
      'Ketentuan'     => ['type' => 'VARCHAR', 'constraint' => 25, 'null' => true],
      'TglAwal'       => ['type' => 'DATE', 'null' => true],
      'TglAkhir'      => ['type' => 'DATE', 'null' => true],
      'Minimum'       => ['type' => 'INT', 'constraint' => 20, 'default' => 0],
      'Status'        => ['type' => 'VARCHAR', 'constraint' => 1, 'default' => '0'],
      'exclude_promo' => ['type' => 'CHAR', 'constraint' => 1, 'null' => true],
      'berlaku'       => ['type' => 'VARCHAR', 'constraint' => 199, 'null' => true],
    ]);
    $this->forge->addKey('NoTrans', true);
    $this->forge->createTable('discountheader');
  }

  public function down()
  {
    $this->forge->dropTable('discountheader');
  }
}
