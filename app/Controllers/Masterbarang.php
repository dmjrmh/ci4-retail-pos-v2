<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MasterbarangModel;

class Masterbarang extends BaseController
{
  protected $items;
  protected $helpers = ['url', 'menu'];

  public function __construct()
  {
    $this->items = new MasterbarangModel();
  }

  public function index()
  {
    $query = $this->request->getGet('query');
    $builder = $this->items;

    if ($query) {
      $builder = $builder->groupStart()
        ->like('PCode', $query)
        ->orLike('NamaLengkap', $query)
        ->orLike('NamaStruk', $query)
        ->groupEnd();
    }

    $data = [
      'title' => 'Master Barang',
      'query' => $query,
      'products' => $builder->orderBy('PCode', 'ASC')->paginate(10),
      'pager' => $builder->pager,
    ];

    return view('masterbarang/index', $data);
  }

  public function create()
  {
    return view('masterbarang/create', [
      'title' => 'Tambah Barang',
      'validation' => \Config\Services::validation()
    ]);
  }

  public function store()
  {
    $rules = [
      'PCode'       => 'required|alpha_numeric_punct|max_length[20]|is_unique[masterbarang.PCode]',
      'NamaLengkap' => 'required|max_length[100]',
      'NamaStruk'   => 'permit_empty|max_length[30]',
      'SatuanSt'    => 'required|in_list[pcs,pack,crat]',
      'Harga1c'     => 'permit_empty|decimal',
      'Harga1b'     => 'permit_empty|decimal',
      'Barcode1'    => 'permit_empty|max_length[20]',
      'Status'      => 'permit_empty|in_list[T,F]'
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = $this->request->getPost([
      'PCode', 'NamaLengkap', 'NamaStruk', 'SatuanSt',
      'Harga1c', 'Harga1b', 'Barcode1', 'Status'
    ]);

    $this->items->insert($data);

    $name = $data['NamaLengkap'] ?? '';
    return redirect()->to(site_url('masterbarang'))
      ->with('success', "Barang " . $name . " berhasil ditambahkan");
  }

  public function edit(string $pcode)
  {
    $pcode = rawurldecode($pcode);
    $item = $this->items->where('PCode', $pcode)->first();
    if (! $item) {
      return redirect()->to(site_url('masterbarang'))
        ->with('errors', ['Data tidak ditemukan']);
    }

    return view('masterbarang/edit', [
      'title' => 'Edit Barang',
      'item'   => $item,
    ]);
  }

  public function update(string $pcode)
  {
    $pcode = rawurldecode($pcode);
    $item = $this->items->where('PCode', $pcode)->first();
    if (! $item) {
      return redirect()->to(site_url('masterbarang'))
        ->with('errors', ['Data tidak ditemukan']);
    }

    $rules = [
      'PCode'       => 'required|alpha_numeric_punct|max_length[20]|is_unique[masterbarang.PCode,PCode,' . $pcode . ']',
      'NamaLengkap' => 'required|max_length[100]',
      'NamaStruk'   => 'permit_empty|max_length[30]',
      'SatuanSt'    => 'required|in_list[pcs,pack,crat]',
      'Harga1c'     => 'permit_empty|decimal',
      'Harga1b'     => 'permit_empty|decimal',
      'Barcode1'    => 'permit_empty|max_length[20]',
      'Status'      => 'permit_empty|in_list[T,F]'
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = $this->request->getPost([
      'PCode', 'NamaLengkap', 'NamaStruk', 'SatuanSt',
      'Harga1c', 'Harga1b', 'Barcode1', 'Status'
    ]);

    $this->items->where('PCode', $pcode)->set($data)->update();

    $name = $data['NamaLengkap'] ?? $item['NamaLengkap'] ?? '';
    return redirect()->to(site_url('masterbarang'))
      ->with('success', "Barang " . $name . " berhasil diupdate");
  }

  public function delete(string $pcode)
  {
    $pcode = rawurldecode($pcode);
    $item = $this->items->where('PCode', $pcode)->first();
    if (! $item) {
      return redirect()->to(site_url('masterbarang'))
        ->with('errors', ['Data tidak ditemukan']);
    }

    $this->items->where('PCode', $pcode)->delete();

    $name = $item['NamaLengkap'] ?? '';

    return redirect()->to(site_url('masterbarang'))
      ->with('success', "Barang " . $name . " berhasil dihapus");
  }
}
