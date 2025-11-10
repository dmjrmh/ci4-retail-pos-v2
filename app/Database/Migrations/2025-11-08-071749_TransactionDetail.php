<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TransactionDetail extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
      'transaction_id'=> ['type' => 'INT', 'unsigned' => true],
      'PCode'         => ['type' => 'VARCHAR', 'constraint' => 20],
      'Qty'           => ['type' => 'INT', 'default' => 1],
      'Harga'         => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => '0.00'],
      'Disc'          => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => '0.00'],
      'Subtotal'      => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => '0.00'],
      'created_at'    => ['type' => 'DATETIME', 'null' => true],
      'updated_at'    => ['type' => 'DATETIME', 'null' => true],
      'deleted_at'    => ['type' => 'DATETIME', 'null' => true],
    ]);

    $this->forge->addKey('id', true);
    $this->forge->addKey('transaction_id', false, false, 'idx_transaction');

    $this->forge->addForeignKey('transaction_id', 'transaction_headers', 'id', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('PCode', 'masterbarang', 'PCode', 'CASCADE', 'CASCADE');

    $this->forge->createTable('transaction_details', true);
  }

  public function down()
  {
    $this->forge->dropTable('transaction_details', true);
  }
}
