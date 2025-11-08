<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TransaksiDetail extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'NoKassa'        => ['type' => 'CHAR', 'constraint' => 3,  'null' => false],
      'Gudang'         => ['type' => 'VARCHAR', 'constraint' => 7, 'null' => true],
      'NoStruk'        => ['type' => 'VARCHAR', 'constraint' => 12, 'null' => false],
      'Tanggal'        => ['type' => 'DATE', 'null' => true],
      'Waktu'          => ['type' => 'TIME', 'null' => true],
      'Kasir'          => ['type' => 'CHAR', 'constraint' => 20, 'null' => true],
      'KdStore'        => ['type' => 'CHAR', 'constraint' => 2, 'null' => true],
      'PCode'          => ['type' => 'CHAR', 'constraint' => 15, 'null' => false],
      'Qty'            => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'Berat'          => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'Harga'          => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'Ketentuan1'     => ['type' => 'CHAR', 'constraint' => 15, 'null' => true],
      'Disc1'          => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'Jenis1'         => ['type' => 'CHAR', 'constraint' => 1, 'null' => true],
      'Ketentuan2'     => ['type' => 'CHAR', 'constraint' => 15, 'null' => true],
      'Disc2'          => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'Jenis2'         => ['type' => 'CHAR', 'constraint' => 1, 'null' => true],
      'Ketentuan3'     => ['type' => 'CHAR', 'constraint' => 15, 'null' => true],
      'Disc3'          => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'Jenis3'         => ['type' => 'CHAR', 'constraint' => 1, 'null' => true],
      'Ketentuan4'     => ['type' => 'CHAR', 'constraint' => 15, 'null' => true],
      'Disc4'          => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'Jenis4'         => ['type' => 'CHAR', 'constraint' => 1, 'null' => true],
      'Netto'          => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
      'Hpp'            => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
      'Status'         => ['type' => 'CHAR', 'constraint' => 1, 'null' => true],
      'Keterangan'     => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
      'Service_charge' => ['type' => 'SMALLINT', 'default' => 0],
      'Komisi'         => ['type' => 'SMALLINT', 'default' => 0],
      'PPN'            => ['type' => 'SMALLINT', 'default' => 0],
      'Printer'        => ['type' => 'VARCHAR', 'constraint' => 2, 'default' => '01'],
      'KdMeja'         => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => '000'],
      'KdAgent'        => ['type' => 'VARCHAR', 'constraint' => 4,  'default' => '0000'],
    ]);

    $this->forge->addKey('Tanggal');
    $this->forge->addForeignKey('PCode', 'masterbarang', 'PCode', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey(['NoKassa', 'NoStruk'], 'transaksi_header', ['NoKassa', 'NoStruk'], 'CASCADE', 'CASCADE');
    $this->forge->createTable('transaksi_detail');
  }

  public function down()
  {
    $this->forge->dropTable('transaksi_detail');
  }
}
