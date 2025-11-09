<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TransactionHeader extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
      'store_id'     => ['type' => 'INT', 'unsigned' => true],
      'NoKassa'      => ['type' => 'CHAR', 'constraint' => 3, 'null' => true],
      'NoStruk'      => ['type' => 'VARCHAR', 'constraint' => 12],
      'Tanggal'      => ['type' => 'DATE'],
      'Waktu'        => ['type' => 'TIME'],
      'Kasir'        => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
      'TotalItem'    => ['type' => 'INT', 'default' => 0],
      'Subtotal'     => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => '0.00'],
      'TotalDiskon'  => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => '0.00'],
      'TotalBayar'   => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => '0.00'],
      'Kembali'      => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => '0.00'],
      'created_at'   => ['type' => 'DATETIME', 'null' => true],
      'updated_at'   => ['type' => 'DATETIME', 'null' => true],
      'deleted_at'   => ['type' => 'DATETIME', 'null' => true],
    ]);

    $this->forge->addKey('id', true);
    $this->forge->addUniqueKey(['NoKassa', 'NoStruk'], 'uq_nokassa_nostruk');
    $this->forge->addKey('Tanggal', false, false, 'idx_th_tanggal');
    $this->forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE');
    $this->forge->createTable('transaction_headers', true);
  }

  public function down()
  {
    $this->forge->dropTable('transaction_headers', true);
  }
}
