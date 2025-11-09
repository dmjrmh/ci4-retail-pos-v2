<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class StoresSeeder extends Seeder
{
  public function run()
  {
    $faker = Factory::create('id_ID');
    $rows = [];
    for ($i = 0; $i < 7; $i++) {
      $rows[] = [
        'name'       => $faker->company,
        'store_code' => strtoupper($faker->bothify('OT##')),
        'address'    => $faker->address,
        'city'       => $faker->city,
        'created_at' => date('Y-m-d H:i:s'),
      ];
    }
    $this->db->table('stores')->insertBatch($rows);
  }
}
