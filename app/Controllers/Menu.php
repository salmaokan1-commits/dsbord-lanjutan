<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\KulinerModel;

class Menu extends BaseController
{
    protected $menuModel;
    protected $kulinerModel;
    
    public function __construct()
    {
        $this->menuModel = new MenuModel();
        $this->kulinerModel = new KulinerModel();
    }
    
    /**
     * Menampilkan daftar menu berdasarkan lokasi kuliner merchant
     */
    public function index()
    {
        // Cek autentikasi login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $role = session()->get('role');
        if ($role !== 'merchant' && $role !== 'developer') {
            return redirect()->to('/kuliner')->with('error', 'Akses ditolak. Fitur ini hanya untuk merchant dan developer.');
        }
        
        $userId = session()->get('id');
        
        // Mengambil daftar kuliner berdasarkan hak akses
        if ($role === 'developer') {
            $kulinerList = $this->kulinerModel->findAll();
        } else {
            $kulinerList = $this->kulinerModel->where('created_by', $userId)->findAll();
        }
        
        $selectedKulinerId = $this->request->getGet('kuliner_id');
        
        $menus = [];
        $selectedKuliner = null;
        
        if ($selectedKulinerId) {
            // Verifikasi kepemilikan jika user adalah merchant
            if ($role === 'merchant') {
                $selectedKuliner = $this->kulinerModel->where('id', $selectedKulinerId)
                    ->where('created_by', $userId)
                    ->first();
                    
                if (!$selectedKuliner) {
                    return redirect()->to('/menu')->with('error', 'Anda tidak memiliki akses ke tempat kuliner tersebut.');
                }
            } else {
                $selectedKuliner = $this->kulinerModel->find($selectedKulinerId);
            }
            
            if ($selectedKuliner) {
                $menus = $this->menuModel->getMenusByKuliner($selectedKulinerId);
            }
        }
        
        $data = [
            'title' => 'Kelola Menu',
            'kulinerList' => $kulinerList,
            'selectedKuliner' => $selectedKuliner,
            'menus' => $menus
        ];
        
        return view('menu/index', $data);
    }
    
    /**
     * Menampilkan form tambah menu baru
     */
    public function create()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $role = session()->get('role');
        if ($role !== 'merchant' && $role !== 'developer') {
            return redirect()->to('/kuliner')->with('error', 'Akses ditolak.');
        }
        
        $kulinerId = $this->request->getGet('kuliner_id');
        if (!$kulinerId) {
            return redirect()->to('/menu')->with('error', 'Pilih tempat kuliner terlebih dahulu.');
        }
        
        $kuliner = $this->verifyKulinerAccess($kulinerId);
        if (!$kuliner) {
            return redirect()->to('/menu')->with('error', 'Akses ditolak ke tempat kuliner tersebut.');
        }
        
        $data = [
            'title' => 'Tambah Menu Baru',
            'kuliner' => $kuliner,
            'menu' => null
        ];
        
        return view('menu/form', $data);
    }
    
    /**
     * Menyimpan data menu baru ke database
     */
    public function store()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $role = session()->get('role');
        if ($role !== 'merchant' && $role !== 'developer') {
            return redirect()->to('/kuliner')->with('error', 'Akses ditolak.');
        }
        
        $kulinerId = $this->request->getPost('kuliner_id');
        
        $kuliner = $this->verifyKulinerAccess($kulinerId);
        if (!$kuliner) {
            return redirect()->to('/menu')->with('error', 'Akses ditolak ke tempat kuliner tersebut.');
        }
        
        // Validasi input form
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_menu' => 'required|min_length[2]|max_length[150]',
            'harga'     => 'required|decimal|greater_than[0]',
            'status'    => 'required|in_list[ready,habis]',
            'foto'      => 'uploaded[foto]|max_size[foto,5120]|is_image[foto]'
        ], [
            'nama_menu' => [
                'required'   => 'Nama menu harus diisi',
                'min_length' => 'Nama menu minimal 2 karakter'
            ],
            'harga' => [
                'required'     => 'Harga harus diisi',
                'decimal'      => 'Harga harus berupa angka',
                'greater_than' => 'Harga harus lebih dari 0'
            ],
            'foto' => [
                'uploaded' => 'Foto menu harus diupload',
                'max_size' => 'Ukuran foto maksimal 5MB',
                'is_image' => 'File harus berupa gambar'
            ]
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $validation->getErrors()));
        }
        
        // Proses upload file gambar
        $foto = $this->request->getFile('foto');
        $namaFoto = null;
        
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $menuDir = ROOTPATH . 'public/img/menus';
            if (!is_dir($menuDir)) {
                mkdir($menuDir, 0755, true);
            }
            
            $namaFoto = $foto->getRandomName();
            $foto->move($menuDir, $namaFoto);
        }
        
        $data = [
            'kuliner_id' => $kulinerId,
            'nama_menu'  => $this->request->getPost('nama_menu'),
            'harga'      => $this->request->getPost('harga'),
            'deskripsi'  => $this->request->getPost('deskripsi'),
            'foto'       => $namaFoto,
            'status'     => $this->request->getPost('status')
        ];
        
        if ($this->menuModel->insert($data)) {
            return redirect()->to('/menu?kuliner_id=' . $kulinerId)->with('success', 'Menu berhasil ditambahkan!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan menu.');
        }
    }
    
    /**
     * Menampilkan form edit data menu
     * @param int $id ID dari Menu
     */
    public function edit(int $id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $role = session()->get('role');
        if ($role !== 'merchant' && $role !== 'developer') {
            return redirect()->to('/kuliner')->with('error', 'Akses ditolak.');
        }
        
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            return redirect()->to('/menu')->with('error', 'Menu tidak ditemukan.');
        }
        
        $kuliner = $this->verifyKulinerAccess($menu['kuliner_id']);
        if (!$kuliner) {
            return redirect()->to('/menu')->with('error', 'Akses ditolak ke menu tersebut.');
        }
        
        $data = [
            'title'   => 'Edit Menu',
            'kuliner' => $kuliner,
            'menu'    => $menu
        ];
        
        return view('menu/form', $data);
    }
    
    /**
     * Memperbarui data menu di database
     * @param int $id ID dari Menu
     */
    public function update(int $id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $role = session()->get('role');
        if ($role !== 'merchant' && $role !== 'developer') {
            return redirect()->to('/kuliner')->with('error', 'Akses ditolak.');
        }
        
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            return redirect()->to('/menu')->with('error', 'Menu tidak ditemukan.');
        }
        
        $kuliner = $this->verifyKulinerAccess($menu['kuliner_id']);
        if (!$kuliner) {
            return redirect()->to('/menu')->with('error', 'Akses ditolak ke menu tersebut.');
        }
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_menu' => 'required|min_length[2]|max_length[150]',
            'harga'     => 'required|decimal|greater_than[0]',
            'status'    => 'required|in_list[ready,habis]',
            'foto'      => 'if_exist|max_size[foto,5120]|is_image[foto]'
        ], [
            'nama_menu' => [
                'required'   => 'Nama menu harus diisi',
                'min_length' => 'Nama menu minimal 2 karakter'
            ],
            'harga' => [
                'required'     => 'Harga harus diisi',
                'decimal'      => 'Harga harus berupa angka',
                'greater_than' => 'Harga harus lebih dari 0'
            ],
            'foto' => [
                'max_size' => 'Ukuran foto maksimal 5MB',
                'is_image' => 'File harus berupa gambar'
            ]
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $validation->getErrors()));
        }
        
        $namaFoto = $menu['foto']; 
        $foto = $this->request->getFile('foto');
        
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            if ($menu['foto']) {
                $oldPath = ROOTPATH . 'public/img/menus/' . $menu['foto'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            
            $menuDir = ROOTPATH . 'public/img/menus';
            if (!is_dir($menuDir)) {
                mkdir($menuDir, 0755, true);
            }
            
            $namaFoto = $foto->getRandomName();
            $foto->move($menuDir, $namaFoto);
        }
        
        $data = [
            'nama_menu' => $this->request->getPost('nama_menu'),
            'harga'     => $this->request->getPost('harga'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'foto'      => $namaFoto,
            'status'    => $this->request->getPost('status')
        ];
        
        if ($this->menuModel->update($id, $data)) {
            return redirect()->to('/menu?kuliner_id=' . $menu['kuliner_id'])->with('success', 'Menu berhasil diupdate!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate menu.');
        }
    }
    
    /**
     * Menghapus data menu dari database beserta filenya
     * @param int $id ID dari Menu
     */
    public function delete(int $id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $role = session()->get('role');
        if ($role !== 'merchant' && $role !== 'developer') {
            return redirect()->to('/kuliner')->with('error', 'Akses ditolak.');
        }
        
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            return redirect()->to('/menu')->with('error', 'Menu tidak ditemukan.');
        }
        
        $kuliner = $this->verifyKulinerAccess($menu['kuliner_id']);
        if (!$kuliner) {
            return redirect()->to('/menu')->with('error', 'Akses ditolak ke menu tersebut.');
        }
        
        if ($menu['foto']) {
            $fotoPath = ROOTPATH . 'public/img/menus/' . $menu['foto'];
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }
        }
        
        if ($this->menuModel->delete($id)) {
            return redirect()->to('/menu?kuliner_id=' . $menu['kuliner_id'])->with('success', 'Menu berhasil dihapus!');
        } else {
            return redirect()->to('/menu')->with('error', 'Gagal menghapus menu.');
        }
    }
    
    /**
     * Verifikasi hak akses merchant ke lokasi kuliner
     */
    private function verifyKulinerAccess($kulinerId)
    {
        $role = session()->get('role');
        
        if ($role === 'developer') {
            return $this->kulinerModel->find($kulinerId);
        }
        
        $userId = session()->get('id');
        return $this->kulinerModel->where('id', $kulinerId)
            ->where('created_by', $userId)
            ->first();
    }
}