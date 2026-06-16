<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Review extends Entity
{
    protected $casts = [
        'rating' => 'integer',
    ];
}
