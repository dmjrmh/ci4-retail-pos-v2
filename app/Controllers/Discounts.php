<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiscountHeaderModel;
use App\Models\DiscountDetailModel;
use App\Models\StoreModel;
use App\Models\MasterbarangModel;

class Discounts extends BaseController
{
  protected $headers;
  protected $details;
  protected $stores;
  protected $items;
  protected $helpers = ['url', 'menu'];

  public function __construct()
  {
    $this->headers = new DiscountHeaderModel();
    $this->details = new DiscountDetailModel();
    $this->stores  = new StoreModel();
    $this->items   = new MasterbarangModel();
  }
  public function index()
  {
    $query = $this->request->getGet('query');
    $builder = $this->headers
      ->select('discount_headers.*, stores.name AS store_name')
      ->join('stores', 'stores.id = discount_headers.store_id', 'left');

    if ($query) {
      $builder = $builder->groupStart()
        ->like('discount_headers.code', $query)
        ->orLike('discount_headers.name', $query)
        ->groupEnd();
    }

    $data = [
      'title'     => 'Diskon',
      'query'     => $query,
      'discounts' => $builder->orderBy('discount_headers.code', 'ASC')->paginate(10),
      'pager'     => $builder->pager,
    ];

    return view('discounts/index', $data);
  }

  public function create()
  {
    $stores = $this->stores->select('id, name, store_code, city')->orderBy('store_code', 'ASC')->findAll();

    return view('discounts/create', [
      'title'      => 'Tambah Diskon',
      'stores'     => $stores,
      'validation' => \Config\Services::validation(),
    ]);
  }
  public function store()
  {
    $rules = [
      'store_id'   => 'permit_empty|integer',
      'code'       => 'required|max_length[30]|is_unique[discount_headers.code]',
      'name'       => 'required|max_length[100]',
      'start_date' => 'required|valid_date[Y-m-d]',
      'end_date'   => 'required|valid_date[Y-m-d]',
      'start_time' => 'permit_empty|regex_match[/^(?:[01]\\d|2[0-3]):[0-5]\\d(?::[0-5]\\d)?$/]',
      'end_time'   => 'permit_empty|regex_match[/^(?:[01]\\d|2[0-3]):[0-5]\\d(?::[0-5]\\d)?$/]',
      'min_amount' => 'permit_empty|decimal',
      'is_active'  => 'required|in_list[0,1]',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = $this->request->getPost([
      'store_id',
      'code',
      'name',
      'start_date',
      'end_date',
      'start_time',
      'end_time',
      'min_amount',
      'is_active'
    ]);

    $data['store_id']   = $data['store_id'] ?: null;
    $data['start_time'] = $data['start_time'] ? (strlen($data['start_time']) === 5 ? $data['start_time'] . ':00' : $data['start_time']) : null;
    $data['end_time']   = $data['end_time']   ? (strlen($data['end_time']) === 5 ? $data['end_time'] . ':00'   : $data['end_time'])   : null;

    $this->headers->insert($data);
    $name = $data['name'];
    $id = $this->headers->getInsertID();

    return redirect()->to(site_url('discounts/edit/' . $id))->with('success', 'Diskon ' . $name . ' berhasil ditambahkan');
  }
  public function edit($id)
  {
    $discount = $this->headers->find($id);

    if (!$discount) {
      return redirect()->to(site_url('discounts'))->with('errors', ['Data tidak ditemukan']);
    }

    $stores = $this->stores->select('id, name, store_code, city')->orderBy('store_code', 'ASC')->findAll();

    $details = $this->details
      ->select('discount_details.*, masterbarang.NamaStruk, masterbarang.NamaLengkap')
      ->join('masterbarang', 'masterbarang.PCode = discount_details.PCode', 'left')
      ->where('discount_id', $id)
      ->orderBy('discount_details.PCode', 'ASC')
      ->findAll();

    $items = $this->items->select('PCode, NamaStruk, NamaLengkap')->orderBy('PCode', 'ASC')->findAll();

    return view('discounts/edit', [
      'title'      => 'Edit Diskon',
      'discount'   => $discount,
      'stores'     => $stores,
      'details'    => $details,
      'items'      => $items,
      'validation' => \Config\Services::validation(),
    ]);
  }

  public function update($id)
  {
    $discount = $this->headers->find($id);
    if (!$discount) {
      return redirect()->to(site_url('discounts'))->with('errors', ['Data tidak ditemukan']);
    }

    $rules = [
      'store_id'   => 'permit_empty|integer',
      'code'       => 'required|max_length[30]|is_unique[discount_headers.code,id,' . $id . ']',
      'name'       => 'required|max_length[100]',
      'start_date' => 'required|valid_date[Y-m-d]',
      'end_date'   => 'required|valid_date[Y-m-d]',
      'start_time' => 'permit_empty|regex_match[/^(?:[01]\\d|2[0-3]):[0-5]\\d(?::[0-5]\\d)?$/]',
      'end_time'   => 'permit_empty|regex_match[/^(?:[01]\\d|2[0-3]):[0-5]\\d(?::[0-5]\\d)?$/]',
      'min_amount' => 'permit_empty|decimal',
      'is_active'  => 'required|in_list[0,1]',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = $this->request->getPost([
      'store_id',
      'code',
      'name',
      'start_date',
      'end_date',
      'start_time',
      'end_time',
      'min_amount',
      'is_active'
    ]);

    $data['store_id']   = $data['store_id'] ?: null;
    $data['start_time'] = $data['start_time'] ? (strlen($data['start_time']) === 5 ? $data['start_time'] . ':00' : $data['start_time']) : null;
    $data['end_time']   = $data['end_time']   ? (strlen($data['end_time']) === 5 ? $data['end_time'] . ':00'   : $data['end_time'])   : null;

    $this->headers->update($id, $data);

    return redirect()->to(site_url('discounts/edit/' . $id))->with('success', 'Diskon berhasil diupdate');
  }

  public function storeDetail($discountId)
  {
    $rules = [
      'PCode' => 'required|max_length[20]|is_not_unique[masterbarang.PCode]',
      'type'  => 'required|in_list[P,R]',
      'value' => 'required|decimal',
    ];
    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $pcode = $this->request->getPost('PCode');
    $type  = $this->request->getPost('type');
    $value = $this->request->getPost('value');

    $existing = $this->details
      ->where('discount_id', $discountId)
      ->where('PCode', $pcode)
      ->first();

    if ($existing) {
      $this->details->update($existing['id'], [
        'type'  => $type,
        'value' => $value,
      ]);
    } else {
      $this->details->insert([
        'discount_id' => $discountId,
        'PCode'       => $pcode,
        'type'        => $type,
        'value'       => $value,
      ]);
    }

    return redirect()->to(site_url('discounts/edit/' . $discountId))->with('success', 'Detail diskon disimpan');
  }

  public function deleteDetail($detailId)
  {
    $detail = $this->details->find($detailId);
    if (!$detail) {
      return redirect()->to(site_url('discounts'))->with('errors', ['Detail tidak ditemukan']);
    }
    $name = $detail['PCode'];

    $this->details->delete($detailId, true);

    return redirect()->to(site_url('discounts/edit/' . $detail['discount_id']))->with('success', 'Detail kode barang ' . $name . ' dihapus');
  }

  public function delete($id) {
    $discount = $this->headers->find($id);

    if (!$discount) {
      return redirect()->to(site_url('discounts'))->with('errors', ['Data tidak ditemukan']);
    }

    $this->headers->where('id', $id)->delete();

    $name = $discount['name'] ?? '';

    return redirect()->to(site_url('discounts'))->with('success', "Diskon " . $name . " berhasil dihapus");
  }
}
