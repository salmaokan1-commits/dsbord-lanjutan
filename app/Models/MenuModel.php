<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table      = 'menus';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'kuliner_id',
        'nama_menu',
        'harga',
        'deskripsi',
        'foto',
        'status',
    ];

    protected $validationRules = [
        'kuliner_id' => 'required|integer',
        'nama_menu'  => 'required|min_length[2]|max_length[150]',
        'harga'      => 'required|decimal',
        'status'     => 'required|in_list[ready,habis]',
    ];

    public function getMenusByKuliner(int $kulinerId)
    {
        return $this->where('kuliner_id', $kulinerId)->orderBy('nama_menu', 'ASC')->findAll();
    }

    public function findByNameAndKuliner(string $name, int $kulinerId)
    {
        return $this->where('nama_menu', $name)
            ->where('kuliner_id', $kulinerId)
            ->first();
    }
}
