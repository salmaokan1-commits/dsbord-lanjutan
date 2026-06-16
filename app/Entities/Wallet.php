<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Wallet extends Entity
{
    protected $casts = [
        'saldo' => 'float',
    ];
}
