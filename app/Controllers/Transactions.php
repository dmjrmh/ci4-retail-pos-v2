<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StoreModel;
use App\Models\MasterbarangModel;
use App\Models\TransactionHeaderModel;
use App\Models\TransactionDetailModel;
use App\Models\DiscountDetailModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Transactions extends BaseController
{
  protected $stores;
  protected $items;
  protected $theader;
  protected $tdetail;
  protected $discdetail;
  protected $helpers = ['url', 'menu'];

  public function __construct()
  {
    $this->stores = new StoreModel();
    $this->items  = new MasterbarangModel();
    $this->theader    = new TransactionHeaderModel();
    $this->tdetail    = new TransactionDetailModel();
    $this->discdetail = new DiscountDetailModel();
  }

  public function index()
  {
    $storeId  = $this->request->getGet('store_id');
    $query    = $this->request->getGet('query');
    $dateFrom = $this->request->getGet('date_from');
    $dateTo   = $this->request->getGet('date_to');

    $builder = $this->theader
      ->select('transaction_headers.*, stores.name AS store_name')
      ->join('stores', 'stores.id = transaction_headers.store_id', 'left');

    if ($storeId) {
      $builder = $builder->where('transaction_headers.store_id', (int)$storeId);
    }
    if ($query) {
      $builder = $builder->groupStart()
        ->like('transaction_headers.NoStruk', $query)
        ->orLike('transaction_headers.Kasir', $query)
        ->groupEnd();
    }
    if ($dateFrom) {
      $builder = $builder->where('transaction_headers.Tanggal >=', $dateFrom);
    }
    if ($dateTo) {
      $builder = $builder->where('transaction_headers.Tanggal <=', $dateTo);
    }

    $transactions = $builder->orderBy('transaction_headers.Tanggal', 'DESC')
      ->orderBy('transaction_headers.Waktu', 'DESC')
      ->paginate(10);

    $stores = $this->stores->select('id, name, store_code, city')->orderBy('store_code', 'ASC')->findAll();

    return view('transactions/index', [
      'title'        => 'Transaksi',
      'stores'       => $stores,
      'transactions' => $transactions,
      'pager'        => $builder->pager,
      'filters'      => [
        'store_id'  => $storeId,
        'query'     => $query,
        'date_from' => $dateFrom,
        'date_to'   => $dateTo,
      ],
    ]);
  }

  public function report()
  {
    $storeId  = $this->request->getGet('store_id');
    $dateFrom = $this->request->getGet('date_from');
    $dateTo   = $this->request->getGet('date_to');

    $builder = $this->theader
      ->select('stores.id AS store_id, stores.name AS store_name, stores.store_code, COUNT(transaction_headers.id) AS trx_count, SUM(transaction_headers.TotalItem) AS total_item, SUM(transaction_headers.Subtotal) AS subtotal, SUM(transaction_headers.TotalDiskon) AS total_diskon, SUM(transaction_headers.TotalBayar) AS total_bayar')
      ->join('stores', 'stores.id = transaction_headers.store_id', 'left');

    if ($storeId) {
      $builder = $builder->where('transaction_headers.store_id', (int) $storeId);
    }
    if ($dateFrom) {
      $builder = $builder->where('transaction_headers.Tanggal >=', $dateFrom);
    }
    if ($dateTo) {
      $builder = $builder->where('transaction_headers.Tanggal <=', $dateTo);
    }

    $summary = $builder->groupBy('stores.id')->orderBy('stores.store_code', 'ASC')->findAll();
    $stores  = $this->stores->select('id, name, store_code, city')->orderBy('store_code', 'ASC')->findAll();

    return view('transactions/report', [
      'title'   => 'Laporan Penjualan',
      'stores'  => $stores,
      'summary' => $summary,
      'filters' => [
        'store_id'  => $storeId,
        'date_from' => $dateFrom,
        'date_to'   => $dateTo,
      ],
    ]);
  }

  public function show($id)
  {
    $trx = $this->theader
      ->select('transaction_headers.*, stores.name AS store_name, stores.store_code')
      ->join('stores', 'stores.id = transaction_headers.store_id', 'left')
      ->where('transaction_headers.id', (int) $id)
      ->first();
    if (!$trx) {
      throw PageNotFoundException::forPageNotFound('Transaksi tidak ditemukan');
    }
    $items = $this->tdetail
      ->select('transaction_details.*, masterbarang.NamaStruk, masterbarang.NamaLengkap')
      ->join('masterbarang', 'masterbarang.PCode = transaction_details.PCode', 'left')
      ->where('transaction_details.transaction_id', (int) $id)
      ->orderBy('transaction_details.id', 'ASC')
      ->findAll();

    return view('transactions/show', [
      'title'  => 'Detail Transaksi',
      'trx'    => $trx,
      'items'  => $items,
    ]);
  }

  public function create()
  {
    $stores = $this->stores->select('id, name, store_code, city')->orderBy('store_code', 'ASC')->findAll();
    $items  = $this->items->select('PCode, NamaStruk, NamaLengkap, Harga1c')
      ->orderBy('PCode', 'ASC')->findAll();
    return view('transactions/create', [
      'title'  => 'Transaksi Baru',
      'stores' => $stores,
      'items'  => $items,
    ]);
  }

  public function findItem($pcode)
  {
    $pcode = rawurldecode($pcode);
    $item = $this->items->where('PCode', $pcode)->first();
    if (!$item) {
      return $this->response->setStatusCode(404)->setJSON(['message' => 'Item not found']);
    }
    return $this->response->setJSON([
      'PCode'   => $item['PCode'],
      'Nama'    => $item['NamaStruk'] ?? $item['NamaLengkap'],
      'Harga1c' => (float) ($item['Harga1c'] ?? 0),
    ]);
  }

  public function store()
  {
    $rules = [
      'store_id' => 'required|integer',
      'Kasir'    => 'permit_empty|max_length[20]',
      'NoKassa'  => 'permit_empty|max_length[3]',
      'pcode'    => 'required',
      'qty'      => 'required',
    ];
    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $storeId = (int) $this->request->getPost('store_id');
    $kasir   = $this->request->getPost('Kasir');
    $noKassa = $this->request->getPost('NoKassa');
    $bayar   = (float) ($this->request->getPost('Bayar') ?? 0);

    $pcodes  = (array) $this->request->getPost('pcode');
    $qtys    = (array) $this->request->getPost('qty');

    $lines = [];
    foreach ($pcodes as $i => $pcode) {
      $pcode = trim((string) $pcode);
      $qty   = (int) ($qtys[$i] ?? 0);
      if ($pcode === '' || $qty <= 0) continue;
      $lines[] = [ 'PCode' => $pcode, 'Qty' => $qty ];
    }
    if (empty($lines)) {
      return redirect()->back()->withInput()->with('errors', ['Tidak ada item valid']);
    }

    $calc = $this->calculateTotals($storeId, $lines);
    if ($calc['error'] ?? null) {
      return redirect()->back()->withInput()->with('errors', [$calc['error']]);
    }
    $lines       = $calc['lines'];
    $totalBefore = $calc['total_before'];
    $totalDisc   = $calc['total_disc'];
    $subtotal    = $calc['total_bayar'];
    $totalItem   = $calc['total_item'];

    $tanggal = date('Y-m-d');
    $waktu   = date('H:i:s');
    
    $nostruk = $this->generateNoStruk($storeId);

    $totalBayar = $subtotal;
    $kembali   = max(0, round($bayar - $totalBayar, 2));

    $headerId = $this->theader->insert([
      'store_id'    => $storeId,
      'NoKassa'     => $noKassa ? substr($noKassa, 0, 3) : null,
      'NoStruk'     => $nostruk,
      'Tanggal'     => $tanggal,
      'Waktu'       => $waktu,
      'Kasir'       => $kasir ?: null,
      'TotalItem'   => $totalItem,
      'Subtotal'    => $subtotal + $totalDisc,
      'TotalDiskon' => $totalDisc,
      'TotalBayar'  => $totalBayar,
      'Kembali'     => $kembali,
    ]);

    foreach ($lines as $ln) {
      $this->tdetail->insert([
        'transaction_id' => $headerId,
        'PCode'          => $ln['PCode'],
        'Qty'            => $ln['Qty'],
        'Harga'          => $ln['Harga'],
        'Disc'           => $ln['Disc'],
        'Subtotal'       => $ln['Subtotal'],
      ]);
    }

    return redirect()->to(site_url('transactions'))
      ->with('success', 'Transaksi tersimpan. NoStruk: ' . $nostruk);
  }

  public function preview()
  {
    $storeId = (int) ($this->request->getPost('store_id') ?? 0);
    $pcodes  = (array) $this->request->getPost('pcode');
    $qtys    = (array) $this->request->getPost('qty');
    if (!$storeId) return $this->response->setStatusCode(400)->setJSON(['error' => 'store_id required']);
    $lines = [];
    foreach ($pcodes as $i => $p) {
      $p = trim((string)$p);
      $q = (int)($qtys[$i] ?? 0);
      if ($p === '' || $q <= 0) continue;
      $lines[] = ['PCode' => $p, 'Qty' => $q];
    }
    if (empty($lines)) return $this->response->setJSON(['lines' => [], 'total_before' => 0, 'total_disc' => 0, 'total_bayar' => 0, 'total_item' => 0]);
    $calc = $this->calculateTotals($storeId, $lines);
    if ($calc['error'] ?? null) return $this->response->setStatusCode(400)->setJSON(['error' => $calc['error']]);
    return $this->response->setJSON($calc);
  }

  private function calculateTotals(int $storeId, array $lines): array
  {
    $pcodeList = array_values(array_unique(array_column($lines, 'PCode')));
    $products  = $this->items->select('PCode, NamaStruk, NamaLengkap, Harga1c')->whereIn('PCode', $pcodeList)->findAll();
    $mapItem = [];
    foreach ($products as $p) $mapItem[$p['PCode']] = $p;
    foreach ($lines as &$ln) {
      if (!isset($mapItem[$ln['PCode']])) {
        return ['error' => 'PCode ' . $ln['PCode'] . ' tidak ditemukan'];
      }
      $item = $mapItem[$ln['PCode']];
      $ln['Nama']  = $item['NamaStruk'] ?? $item['NamaLengkap'] ?? '';
      $ln['Harga'] = (float) ($item['Harga1c'] ?? 0);
    }
    unset($ln);

    $totalBefore = 0.0;
    foreach ($lines as $ln) $totalBefore += $ln['Qty'] * $ln['Harga'];

    $nowDate = date('Y-m-d');
    $nowTime = date('H:i:s');

    $discountRows = $this->discdetail
      ->select('discount_details.*, discount_headers.store_id, discount_headers.start_date, discount_headers.end_date, discount_headers.start_time, discount_headers.end_time, discount_headers.min_amount, discount_headers.is_active')
      ->join('discount_headers', 'discount_headers.id = discount_details.discount_id', 'inner')
      ->where('discount_headers.is_active', 1)
      ->where('discount_headers.start_date <=', $nowDate)
      ->where('discount_headers.end_date >=', $nowDate)
      ->whereIn('discount_details.PCode', $pcodeList)
      ->groupStart()->where('discount_headers.store_id', $storeId)->orWhere('discount_headers.store_id', null)->groupEnd()
      ->groupStart()
        ->groupStart()->where('discount_headers.start_time', null)->orWhere('discount_headers.start_time <=', $nowTime)->groupEnd()
        ->groupStart()->where('discount_headers.end_time', null)->orWhere('discount_headers.end_time >=', $nowTime)->groupEnd()
      ->groupEnd()
      ->findAll();

    $byPcode = [];
    foreach ($discountRows as $r) {
      $minAmount = (float) ($r['min_amount'] ?? 0);
      if ((int) round($totalBefore * 100) < (int) round($minAmount * 100)) continue;
      $byPcode[$r['PCode']][] = $r;
    }

    $totalDisc = 0.0; $totalAfter = 0.0; $totalItem = 0;
    foreach ($lines as &$ln) {
      $lineTotal = $ln['Qty'] * $ln['Harga'];
      $disc = 0.0;
      if (!empty($byPcode[$ln['PCode']])) {
        foreach ($byPcode[$ln['PCode']] as $d) {
          $cand = 0.0;
          if ($d['type'] === 'P') $cand = round($lineTotal * (float)$d['value'] / 100, 2);
          else $cand = round((float)$d['value'] * $ln['Qty'], 2);
          if ($cand > $lineTotal) $cand = $lineTotal;
          if ($cand > $disc) $disc = $cand;
        }
      }
      $ln['Disc'] = $disc;
      $ln['Subtotal'] = $lineTotal - $disc;
      $totalDisc += $disc; $totalAfter += $ln['Subtotal']; $totalItem += $ln['Qty'];
    }
    unset($ln);

    return [
      'lines'        => $lines,
      'total_before' => $totalBefore,
      'total_disc'   => $totalDisc,
      'total_bayar'  => $totalAfter,
      'total_item'   => $totalItem,
    ];
  }

  public function activeDiscounts()
  {
    $storeId = (int) ($this->request->getGet('store_id') ?? 0);
    $pcode   = $this->request->getGet('pcode');
    if (!$storeId) {
      return $this->response->setStatusCode(400)->setJSON(['message' => 'store_id required']);
    }
    $today = date('Y-m-d');
    $now   = date('H:i:s');
    $builder = $this->discdetail
      ->join('discount_headers', 'discount_headers.id = discount_details.discount_id', 'inner')
      ->where('discount_headers.is_active', 1)
      ->where('discount_headers.start_date <=', $today)
      ->where('discount_headers.end_date >=', $today)
      ->groupStart()->where('discount_headers.store_id', $storeId)->orWhere('discount_headers.store_id', null)->groupEnd()
      ->groupStart()
        ->groupStart()->where('discount_headers.start_time', null)->orWhere('discount_headers.start_time <=', $now)->groupEnd()
        ->groupStart()->where('discount_headers.end_time', null)->orWhere('discount_headers.end_time >=', $now)->groupEnd()
      ->groupEnd();

    if ($pcode) {
      $builder = $builder->select('discount_headers.id, discount_headers.code, discount_headers.name, discount_headers.start_date, discount_headers.end_date, discount_headers.start_time, discount_headers.end_time, discount_headers.min_amount, discount_details.PCode, discount_details.type, discount_details.value')
        ->where('discount_details.PCode', $pcode)
        ->orderBy('discount_headers.code', 'ASC');
      $rows = $builder->findAll();
      return $this->response->setJSON($rows);
    }

    $rows = $builder
      ->select('discount_headers.id, discount_headers.code, discount_headers.name, discount_headers.start_date, discount_headers.end_date, discount_headers.start_time, discount_headers.end_time, discount_headers.min_amount, discount_details.PCode, discount_details.type, discount_details.value, masterbarang.NamaStruk, masterbarang.NamaLengkap')
      ->join('masterbarang', 'masterbarang.PCode = discount_details.PCode', 'left')
      ->orderBy('discount_headers.code', 'ASC')
      ->orderBy('discount_details.PCode', 'ASC')
      ->findAll();

    $grouped = [];
    foreach ($rows as $r) {
      $id = (int)$r['id'];
      if (!isset($grouped[$id])) {
        $grouped[$id] = [
          'id'         => $id,
          'code'       => $r['code'],
          'name'       => $r['name'],
          'start_date' => $r['start_date'],
          'end_date'   => $r['end_date'],
          'start_time' => $r['start_time'],
          'end_time'   => $r['end_time'],
          'min_amount' => $r['min_amount'],
          'items'      => [],
        ];
      }
      $grouped[$id]['items'][] = [
        'PCode' => $r['PCode'],
        'Nama'  => $r['NamaStruk'] ?? $r['NamaLengkap'] ?? '',
        'type'  => $r['type'],
        'value' => $r['value'],
      ];
    }

    return $this->response->setJSON(array_values($grouped));
  }

  private function generateNoStruk(int $storeId): string
  {
    $store = $this->stores->select('store_code')->where('id', $storeId)->first();
    $code  = strtoupper(preg_replace('/[^A-Z0-9]/', '', (string)($store['store_code'] ?? '')));
    if ($code === '') $code = (string)$storeId;
    
    if (strlen($code) >= 3) {
      $code3 = substr($code, 0, 3);
    } else {
      $code3 = str_pad($code, 3, '0', STR_PAD_LEFT);
    }

    $date6 = date('ymd');
    $prefix = $code3 . $date6;

    $row = $this->theader
      ->select('MAX(NoStruk) AS max_code')
      ->like('NoStruk', $prefix, 'after')
      ->first();
    $max = $row['max_code'] ?? null;
    $seq = 0;
    if ($max && strlen($max) >= 12) {
      $tail = substr($max, -3);
      if (ctype_digit($tail)) $seq = (int)$tail;
    }
    $seq = ($seq + 1) % 1000;
    $seq3 = str_pad((string)$seq, 3, '0', STR_PAD_LEFT);

    return $prefix . $seq3;
  }
}
