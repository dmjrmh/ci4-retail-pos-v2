<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MasterBarang extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
      'PCode'       => ['type' => 'VARCHAR', 'constraint' => 20, 'unique' => true],
      'NamaLengkap' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
      'NamaStruk'   => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => ''],
      'SatuanSt'    => ['type' => 'CHAR', 'constraint' => 10, 'default' => 'pcs'],
      'Harga1c'     => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
      'Harga1b'     => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
      'Barcode1'    => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
      'Status'      => ['type' => 'CHAR', 'constraint' => 1, 'default' => 'T'],
      'created_at'  => ['type' => 'DATETIME', 'null' => true],
      'updated_at'  => ['type' => 'DATETIME', 'null' => true],
      'deleted_at'  => ['type' => 'DATETIME', 'null' => true],
    ]);

    $this->forge->addKey('id', true);
    $this->forge->createTable('masterbarang');
  }

  public function down()
  {
    $this->forge->dropTable('masterbarang', true);
  }
}
