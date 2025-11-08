<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TransaksiHeader extends Migration
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
      'TotalItem'      => ['type' => 'INT', 'constraint' => 4, 'null' => true],
      'TotalNilaiPem'  => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'TotalNilai'     => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'TotalBayar'     => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'Kembali'        => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'Point'          => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'Tunai'          => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'KKredit'        => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'KDebit'         => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'GoPay'          => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'Voucher'        => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'VoucherTravel'  => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'Discount'       => ['type' => 'DECIMAL', 'constraint' => '10,0', 'default' => '0'],
      'BankDebet'      => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
      'EDCBankDebet'   => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
      'BankKredit'     => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
      'EDCBankKredit'  => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
      'Status'         => ['type' => 'CHAR', 'constraint' => 1, 'null' => true],
      'KdCustomer'     => ['type' => 'CHAR', 'constraint' => 15, 'null' => true],
      'Ttl_Charge'     => ['type' => 'INT', 'default' => 0],
      'DPP'            => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
      'TAX'            => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
      'KdMeja'         => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => '000'],
      'userdisc'       => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => ''],
      'KdMember'       => ['type' => 'VARCHAR', 'constraint' => 25, 'default' => ''],
      'NoCard'         => ['type' => 'VARCHAR', 'constraint' => 25, 'default' => ''],
      'NamaCard'       => ['type' => 'VARCHAR', 'constraint' => 25, 'default' => ''],
      'nilaidisc'      => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => '0.00'],
      'statuskomisi'   => ['type' => 'CHAR', 'constraint' => 1, 'default' => '0'],
      'statuskomisi_khusus' => ['type' => 'CHAR', 'constraint' => 1, 'default' => '0'],
    ]);

    $this->forge->addKey(['NoKassa', 'NoStruk'], true);
    $this->forge->createTable('transaksi_header');
  }

  public function down()
  {
    $this->forge->dropTable('transaksi_header');
  }
}
