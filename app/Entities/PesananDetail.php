<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PesananDetail extends Entity
{
    protected $casts = [
        'qty' => 'integer',
        'subtotal' => 'float',
    ];
}
