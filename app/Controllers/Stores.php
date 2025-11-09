<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StoreModel;

class Stores extends BaseController
{
  protected $outlets;
  protected $helpers = ['url', 'menu'];

  public function __construct()
  {
    $this->outlets = new StoreModel();
  }

  public function index()
  {
    $query = $this->request->getGet('query');
    $builder = $this->outlets;

    if ($query) {
      $builder = $builder->groupStart()
        ->like('store_code', $query)
        ->orLike('name', $query)
        ->groupEnd();
    }
    $data = [
      'title' => 'Outlet',
      'query' => $query,
      'outlets' => $builder->orderBy('store_code', 'ASC')->paginate(10),
      'pager' => $builder->pager,
    ];

    return view('stores/index', $data);
  }

  public function create()
  {
    return view('stores/create', [
      'title' => 'Tambah Outlet',
      'validation' => \Config\Services::validation()
    ]);
  }

  public function store()
  {
    $rules = [
      'name'        => 'required|max_length[100]',
      'store_code'  => 'permit_empty|max_length[20]|is_unique[stores.store_code]',
      'address'     => 'permit_empty',
      'city'        => 'permit_empty|max_length[50]',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = $this->request->getPost([
      'name',
      'store_code',
      'address',
      'city',
    ]);

    $this->outlets->insert($data);

    $name = $data['name'] ?? '';
    return redirect()->to(site_url('stores'))->with('success', "Outlet " . $name . " berhasil ditambahkan");
  }

  public function edit($id)
  {
    $outlet = $this->outlets->where('id', $id)->first();
    if (! $outlet) {
      return redirect()->to(site_url('stores'))
        ->with('errors', ['Data tidak ditemukan']);
    }

    return view('stores/edit', [
      'title'    => 'Edit Outlet',
      'outlet'   => $outlet,
    ]);
  }

  public function update($id)
  {
    $rules = [
      'name'        => 'required|max_length[100]',
      'store_code'  => 'permit_empty|max_length[20]|is_unique[stores.store_code,id,' . $id . ']',
      'address'     => 'permit_empty',
      'city'        => 'permit_empty|max_length[50]',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = $this->request->getPost([
      'name',
      'store_code',
      'address',
      'city',
    ]);

    $this->outlets->update($id, $data);
    $name = $data['name'];

    return redirect()->to(site_url('stores'))->with('success', "Outlet " . $name . " berhasil diupdate");
  }

  public function delete($id)
  {
    $outlet = $this->outlets->find($id);

    if (!$outlet) {
      return redirect()->to(site_url('stores'))->with('errors', ['Data tidak ditemukan']);
    }

    $this->outlets->where('id', $id)->delete();

    $name = $outlet['name'] ?? '';

    return redirect()->to(site_url('stores'))->with('success', "Outlet " . $name . " berhasil dihapus");
  }
}
