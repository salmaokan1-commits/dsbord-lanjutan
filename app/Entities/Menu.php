<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Menu extends Entity
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'harga' => 'float',
    ];
}
