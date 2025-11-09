<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DiscountHeader extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
      'store_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
      'code'       => ['type' => 'VARCHAR', 'constraint' => 30],
      'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
      'start_date' => ['type' => 'DATE', 'null' => false],
      'end_date'   => ['type' => 'DATE', 'null' => false],
      'start_time' => ['type' => 'TIME', 'null' => true],
      'end_time'   => ['type' => 'TIME', 'null' => true],
      'min_amount' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => '0.00'],
      'is_active'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
      'created_at' => ['type' => 'DATETIME', 'null' => true],
      'updated_at' => ['type' => 'DATETIME', 'null' => true],
      'deleted_at' => ['type' => 'DATETIME', 'null' => true],
    ]);

    $this->forge->addKey('id', true);
    $this->forge->addUniqueKey('code');
    $this->forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'SET NULL');
    $this->forge->createTable('discount_headers', true);
  }

  public function down()
  {
    $this->forge->dropTable('discount_headers', true);
  }
}
