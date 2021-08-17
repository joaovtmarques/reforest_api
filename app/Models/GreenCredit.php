<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GreenCredit extends Model
{
    use HasFactory;

    protected $table = 'greencredits';
    public $timestamps = false;
}
