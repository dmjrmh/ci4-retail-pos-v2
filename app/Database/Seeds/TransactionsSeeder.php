<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class TransactionsSeeder extends Seeder
{
  public function run()
  {
    $faker = Factory::create('id_ID');

    // Load stores and items
    $stores = $this->db->table('stores')->select('id, store_code, name')->get()->getResultArray();
    $items  = $this->db->table('masterbarang')->select('PCode, NamaStruk, NamaLengkap, Harga1c')->get()->getResultArray();
    if (empty($stores) || empty($items)) {
      echo "Skip: stores/masterbarang kosong.\n";
      return;
    }

    $nowDate = date('Y-m-d');
    $minDate = date('Y-m-d', strtotime('-5 days'));

    foreach ($stores as $store) {
      // Generate 8-15 transactions per store
      $count = rand(8, 15);
      for ($i = 0; $i < $count; $i++) {
        // Random date/time within last 5 days up to today
        $tanggal = $faker->dateTimeBetween($minDate.' 00:00:00', $nowDate.' 23:59:59');
        $tglStr  = $tanggal->format('Y-m-d');
        $jamStr  = $tanggal->format('H:i:s');

        // Pick 2-5 items for this transaction
        $nItems = rand(2, 5);
        $picked = [];
        $lines  = [];
        for ($j = 0; $j < $nItems; $j++) {
          $it = $items[array_rand($items)];
          $p  = $it['PCode'];
          if (isset($picked[$p])) { $j--; continue; }
          $picked[$p] = true;
          $qty   = rand(1, 3);
          $harga = (float)($it['Harga1c'] ?? 0);
          $lines[] = [ 'PCode' => $p, 'Qty' => $qty, 'Harga' => $harga ];
        }

        // Calculate totals + apply discounts similar to app logic
        $totalBefore = 0.0; $totalItem = 0;
        foreach ($lines as $ln) { $totalBefore += $ln['Qty'] * $ln['Harga']; $totalItem += $ln['Qty']; }

        // Get applicable discounts for these items at that outlet/time
        $pcodeList = array_column($lines, 'PCode');
        $discRows = $this->db->table('discount_details')
          ->select('discount_details.PCode, discount_details.type, discount_details.value, discount_headers.store_id, discount_headers.start_date, discount_headers.end_date, discount_headers.start_time, discount_headers.end_time, discount_headers.min_amount, discount_headers.is_active')
          ->join('discount_headers', 'discount_headers.id = discount_details.discount_id', 'inner')
          ->where('discount_headers.is_active', 1)
          ->where('discount_headers.start_date <=', $tglStr)
          ->where('discount_headers.end_date >=', $tglStr)
          ->groupStart()->where('discount_headers.store_id', $store['id'])->orWhere('discount_headers.store_id', null)->groupEnd()
          ->whereIn('discount_details.PCode', $pcodeList)
          ->get()->getResultArray();

        // Filter by time and min_amount
        $byPcode = [];
        foreach ($discRows as $r) {
          $okTime = true;
          if (!empty($r['start_time']) && $r['start_time'] > $jamStr) $okTime = false;
          if (!empty($r['end_time'])   && $r['end_time']   < $jamStr) $okTime = false;
          if (!$okTime) continue;
          $minAmt = (float)($r['min_amount'] ?? 0);
          if ($totalBefore + 1e-6 < $minAmt) continue;
          $byPcode[$r['PCode']][] = $r;
        }

        $totalDisc = 0.0; $totalAfter = 0.0;
        foreach ($lines as &$ln) {
          $lineTotal = $ln['Qty'] * $ln['Harga'];
          $disc = 0.0;
          if (!empty($byPcode[$ln['PCode']])) {
            foreach ($byPcode[$ln['PCode']] as $d) {
              $cand = ($d['type'] === 'P') ? round($lineTotal * (float)$d['value'] / 100, 2)
                                           : round((float)$d['value'] * $ln['Qty'], 2);
              if ($cand > $lineTotal) $cand = $lineTotal;
              if ($cand > $disc) $disc = $cand;
            }
          }
          $ln['Disc'] = $disc;
          $ln['Subtotal'] = $lineTotal - $disc;
          $totalDisc += $disc; $totalAfter += $ln['Subtotal'];
        }
        unset($ln);

        // Build NoStruk similar to controller logic (3-char outlet code + yymmdd + 3-digit seq)
        $code = strtoupper(preg_replace('/[^A-Z0-9]/', '', (string)($store['store_code'] ?? '')));
        if ($code === '') $code = (string)$store['id'];
        $code3 = strlen($code) >= 3 ? substr($code, 0, 3) : str_pad($code, 3, '0', STR_PAD_LEFT);
        $date6 = $tanggal->format('ymd');
        $prefix = $code3 . $date6;
        $maxRow = $this->db->table('transaction_headers')
          ->select('MAX(NoStruk) AS max_code')
          ->like('NoStruk', $prefix, 'after')
          ->get()->getRowArray();
        $max = $maxRow['max_code'] ?? null; $seq = 0;
        if ($max && strlen($max) >= 12) {
          $tail = substr($max, -3);
          if (ctype_digit($tail)) $seq = (int)$tail;
        }
        $seq = ($seq + 1) % 1000; $seq3 = str_pad((string)$seq, 3, '0', STR_PAD_LEFT);
        $nostruk = $prefix . $seq3;

        // Insert header
        $this->db->table('transaction_headers')->insert([
          'store_id'    => $store['id'],
          'NoKassa'     => str_pad((string)rand(1, 3), 3, '0', STR_PAD_LEFT),
          'NoStruk'     => $nostruk,
          'Tanggal'     => $tglStr,
          'Waktu'       => $jamStr,
          'Kasir'       => $faker->firstName(),
          'TotalItem'   => $totalItem,
          'Subtotal'    => $totalAfter + $totalDisc,
          'TotalDiskon' => $totalDisc,
          'TotalBayar'  => $totalAfter,
          'Kembali'     => 0,
          'created_at'  => date('Y-m-d H:i:s'),
        ]);
        $trxId = $this->db->insertID();

        // Insert details
        $detailRows = [];
        foreach ($lines as $ln) {
          $detailRows[] = [
            'transaction_id' => $trxId,
            'PCode'          => $ln['PCode'],
            'Qty'            => $ln['Qty'],
            'Harga'          => $ln['Harga'],
            'Disc'           => $ln['Disc'],
            'Subtotal'       => $ln['Subtotal'],
            'created_at'     => date('Y-m-d H:i:s'),
          ];
        }
        if (!empty($detailRows)) {
          $this->db->table('transaction_details')->insertBatch($detailRows);
        }
      }
    }
  }
}

