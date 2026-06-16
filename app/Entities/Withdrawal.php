<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Withdrawal extends Entity
{
    protected $dates = [
        'approved_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'jumlah_tarik' => 'float',
    ];
}
