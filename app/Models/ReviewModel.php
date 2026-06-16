<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table      = 'reviews';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'menu_id',
        'user_id',
        'rating',
        'ulasan',
    ];

    protected $validationRules = [
        'menu_id' => 'required|integer',
        'user_id' => 'required|integer',
        'rating'  => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
    ];

    public function getAverageRatingByKuliner(int $kulinerId)
    {
        return $this->select('AVG(reviews.rating) AS avg_rating')
            ->join('menus', 'menus.id = reviews.menu_id')
            ->where('menus.kuliner_id', $kulinerId)
            ->get()
            ->getRowArray();
    }
}
