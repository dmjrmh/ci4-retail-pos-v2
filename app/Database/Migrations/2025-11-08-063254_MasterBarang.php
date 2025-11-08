<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MasterBarang extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'PCode'          => ['type' => 'CHAR', 'constraint' => 15, 'null' => false],
      'NamaLengkap'    => ['type' => 'VARCHAR', 'constraint' => 75, 'default' => ''],
      'NamaStruk'      => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => ''],
      'NamaInitial'    => ['type' => 'VARCHAR', 'constraint' => 75, 'default' => ''],
      'SatuanSt'       => ['type' => 'CHAR', 'constraint' => 10, 'default' => ''],
      'SatuanBl'       => ['type' => 'CHAR', 'constraint' => 3,  'default' => ''],
      'KonvBlSt'       => ['type' => 'DECIMAL', 'constraint' => '10,3', 'default' => '0.000'],
      'Harga0b'        => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
      'Satuan1'        => ['type' => 'CHAR', 'constraint' => 3, 'default' => ''],
      'Konv1st'        => ['type' => 'DECIMAL', 'constraint' => '10,3', 'default' => '0.000'],
      'Harga1c'        => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00', 'comment' => 'Harga Jual'],
      'Harga1t'        => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
      'Harga1b'        => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00', 'comment' => 'Harga Beli'],
      'Satuan2'        => ['type' => 'CHAR', 'constraint' => 3, 'default' => ''],
      'JenisBarang'    => ['type' => 'CHAR', 'constraint' => 3, 'default' => ''],
      'Barcode1'       => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => ''],
      'Barcode2'       => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => ''],
      'Barcode3'       => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => ''],
      'KdDivisi'       => ['type' => 'CHAR', 'constraint' => 2, 'default' => ''],
      'KdSubDivisi'    => ['type' => 'CHAR', 'constraint' => 4, 'default' => ''],
      'KdKategori'     => ['type' => 'CHAR', 'constraint' => 3, 'default' => ''],
      'KdSubKategori'  => ['type' => 'CHAR', 'constraint' => 4, 'default' => ''],
      'KdBrand'        => ['type' => 'CHAR', 'constraint' => 2, 'default' => ''],
      'KdSubBrand'     => ['type' => 'CHAR', 'constraint' => 4, 'default' => ''],
      'Panjang'        => ['type' => 'CHAR', 'constraint' => 3, 'default' => ''],
      'Lebar'          => ['type' => 'CHAR', 'constraint' => 3, 'default' => ''],
      'Tinggi'         => ['type' => 'CHAR', 'constraint' => 3, 'default' => ''],
      'HargaBeli'      => ['type' => 'DECIMAL', 'constraint' => '12,0', 'default' => '0'],
      'PersenPajak'    => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => '0.00'],
      'Tipe'           => ['type' => 'CHAR', 'constraint' => 1, 'default' => ''],
      'Status'         => ['type' => 'CHAR', 'constraint' => 1, 'default' => 'T'],
      'AddDate'        => ['type' => 'DATETIME', 'null' => true],
      'EditDate'       => ['type' => 'DATETIME', 'null' => true],
      'KdKomisi'       => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
      'Service_charge' => ['type' => 'INT', 'constraint' => 3, 'default' => 0],
      'JenisPajak'     => ['type' => 'VARCHAR', 'constraint' => 3, 'default' => 'PPN', 'comment' => 'PPN atau PB1'],
      'HPP'            => ['type' => 'INT', 'default' => 0],
      'StatusPajak'    => ['type' => 'CHAR', 'constraint' => 1, 'default' => '0', 'comment' => '1 include ppn 0 exclude ppn'],
      'FlagReady'      => ['type' => 'CHAR', 'constraint' => 1, 'default' => 'Y'],
      'Printer'        => ['type' => 'CHAR', 'constraint' => 2, 'default' => '01'],
      'Jenis'          => ['type' => 'CHAR', 'constraint' => 1, 'default' => '1'],
      'komisi'         => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => '0.00'],
      'DiscInternal'   => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => '0.00'],
    ]);

    $this->forge->addKey('PCode', true);
    $this->forge->createTable('masterbarang');
  }

  public function down()
  {
    $this->forge->dropTable('masterbarang');
  }
}
