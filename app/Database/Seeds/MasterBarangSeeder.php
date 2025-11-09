<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterBarangSeeder extends Seeder
{
  public function run()
  {
    $items = [
      [
        'PCode'       => 'IND-GRG-085',
        'NamaLengkap' => 'Indomie Goreng 85g',
        'NamaStruk'   => 'INDOMIE GRG 85G',
        'SatuanSt'    => 'pcs',
        'Harga1c'     => 3500,
        'Harga1b'     => 3000,
        'Barcode1'    => '8991234500001',
        'Status'      => 'T',
      ],
      [
        'PCode'       => 'IND-RND-085',
        'NamaLengkap' => 'Indomie Rendang 85g Pack',
        'NamaStruk'   => 'INDOMIE RND 85G PK',
        'SatuanSt'    => 'pack',
        'Harga1c'     => 17500,
        'Harga1b'     => 15000,
        'Barcode1'    => '8991234500002',
        'Status'      => 'T',
      ],
      [
        'PCode'       => 'GULA-1KG',
        'NamaLengkap' => 'Gula Pasir 1kg',
        'NamaStruk'   => 'GULA 1KG',
        'SatuanSt'    => 'pcs',
        'Harga1c'     => 18000,
        'Harga1b'     => 16000,
        'Barcode1'    => '8991234500003',
        'Status'      => 'T',
      ],
      [
        'PCode'       => 'MINYAK-2L',
        'NamaLengkap' => 'Minyak Goreng 2L Refill',
        'NamaStruk'   => 'MNYK GRG 2L REF',
        'SatuanSt'    => 'pcs',
        'Harga1c'     => 40500,
        'Harga1b'     => 35000,
        'Barcode1'    => '8991234500004',
        'Status'      => 'T',
      ],
      [
        'PCode'       => 'TEHBOT-350',
        'NamaLengkap' => 'Teh Botol 350ml',
        'NamaStruk'   => 'TEHBOTOL 350ML',
        'SatuanSt'    => 'pcs',
        'Harga1c'     => 6000,
        'Harga1b'     => 5200,
        'Barcode1'    => '8991234500005',
        'Status'      => 'T',
      ],
      [
        'PCode'       => 'TEHBOT-6PK',
        'NamaLengkap' => 'Teh Botol 350ml 6 Pack',
        'NamaStruk'   => 'TEHBOTOL 6PK',
        'SatuanSt'    => 'pack',
        'Harga1c'     => 29000,
        'Harga1b'     => 25000,
        'Barcode1'    => '8991234500006',
        'Status'      => 'T',
      ],
      [
        'PCode'       => 'AIR-MNRL-600',
        'NamaLengkap' => 'Air Mineral 600ml',
        'NamaStruk'   => 'AQUA 600ML',
        'SatuanSt'    => 'pcs',
        'Harga1c'     => 3000,
        'Harga1b'     => 2000,
        'Barcode1'    => '8991234500007',
        'Status'      => 'T',
      ],
      [
        'PCode'       => 'AIR-MNRL-24',
        'NamaLengkap' => 'Air Mineral 600ml 24 Crate',
        'NamaStruk'   => 'AQUA 600 1 CRT',
        'SatuanSt'    => 'crat',
        'Harga1c'     => 60000,
        'Harga1b'     => 50000,
        'Barcode1'    => '8991234500008',
        'Status'      => 'T',
      ],
      [
        'PCode'       => 'SUSU-UHT-1L',
        'NamaLengkap' => 'Susu UHT 1 Liter',
        'NamaStruk'   => 'SUSU UHT 1L',
        'SatuanSt'    => 'pcs',
        'Harga1c'     => 19500,
        'Harga1b'     => 18000,
        'Barcode1'    => '8991234500009',
        'Status'      => 'T',
      ],
      [
        'PCode'       => 'KOPI-SACH-10',
        'NamaLengkap' => 'Kopi Sachet 10 Pack',
        'NamaStruk'   => 'KOPI 10PK',
        'SatuanSt'    => 'pack',
        'Harga1c'     => 17500,
        'Harga1b'     => 16000,
        'Barcode1'    => '8991234500010',
        'Status'      => 'T',
      ],
    ];

    $this->db->table('masterbarang')->insertBatch($items);
  }
}
