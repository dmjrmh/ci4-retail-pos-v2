<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class DiscountsSeeder extends Seeder
{
  public function run()
  {
    $faker = Factory::create('id_ID');

    $storeIds = array_column(
      $this->db->table('stores')->select('id')->get()->getResultArray(),
      'id'
    );
    $pcodes = array_column(
      $this->db->table('masterbarang')->select('PCode')->get()->getResultArray(),
      'PCode'
    );

    if (empty($pcodes)) {
      echo "Skip: masterbarang kosong.\n";
      return;
    }

    $now     = date('Y-m-d H:i:s');
    $today   = date('Y-m-d');
    $end30   = date('Y-m-d', strtotime('+30 days'));
    $headers = [];

    // Seed 5 header
    for ($i = 0; $i < 5; $i++) {
      $headers[] = [
        'store_id'   => !empty($storeIds) ? $storeIds[array_rand($storeIds)] : null,
        'code'       => strtoupper($faker->bothify('DISC-####')),
        'name'       => 'Promo ' . ucfirst($faker->word),
        'start_date' => $today,
        'end_date'   => $end30,
        'start_time' => rand(0, 1) ? null : '08:00:00',
        'end_time'   => rand(0, 1) ? null : '21:00:00',
        'min_amount' => $faker->randomFloat(2, 0, 100000),
        'is_active'  => 1,
        'created_at' => $now,
      ];
    }

    $this->db->table('discount_headers')->insertBatch($headers);

    $codes = array_column($headers, 'code');
    $rows  = $this->db->table('discount_headers')
      ->select('id, code')
      ->whereIn('code', $codes)
      ->get()->getResultArray();

    $codeToId = [];
    foreach ($rows as $r) $codeToId[$r['code']] = $r['id'];

    $details = [];
    foreach ($headers as $h) {
      $discountId = $codeToId[$h['code']] ?? null;
      if (!$discountId) continue;

      $picked = [];
      $count  = rand(2, 3);
      for ($j = 0; $j < $count; $j++) {
        $pcode = $pcodes[array_rand($pcodes)];
        if (isset($picked[$pcode])) {
          $j--;
          continue;
        }
        $picked[$pcode] = true;

        $type  = rand(0, 1) ? 'P' : 'R';
        $value = $type === 'P'
          ? $faker->randomFloat(3, 5, 30)       
          : $faker->randomFloat(3, 1000, 10000);

        $details[] = [
          'discount_id' => $discountId,
          'PCode'       => $pcode,
          'type'        => $type,
          'value'       => $value,
          'created_at'  => $now,
        ];
      }
    }

    if (!empty($details)) {
      $this->db->table('discount_details')->insertBatch($details);
    }
  }
}
