<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiHeaderModel extends Model
{
  protected $table            = 'transaksi_header';
  protected $returnType       = 'array';
  protected $allowedFields = [
    'NoKassa', 'Gudang', 'NoStruk', 'Tanggal', 'Waktu', 'Kasir',
    'KdStore', 'TotalItem', 'TotalNilaiPem', 'TotalNilai', 'TotalBayar',
    'Kembali', 'Point', 'Tunai', 'KKredit', 'KDebit', 'GoPay',
    'Voucher', 'VoucherTravel', 'Discount', 'BankDebet', 'EDCBankDebet',
    'BankKredit', 'EDCBankKredit', 'Status', 'KdCustomer', 'Ttl_Charge',
    'DPP', 'TAX', 'KdMeja', 'userdisc', 'KdMember', 'NoCard', 'NamaCard',
    'nilaidisc', 'statuskomisi', 'statuskomisi_khusus'
  ];
}
