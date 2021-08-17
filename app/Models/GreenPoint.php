<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GreenPoint extends Model
{
    use HasFactory;

    protected $table = 'greenpoints';
    public $timestamps = false;
}
