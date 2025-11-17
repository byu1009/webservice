<?php

namespace App\Models\Khanza;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    public $timestamps = false;
    protected $connection = "second_db";
    protected $table = "pegawai";
    protected $keyType = 'int';
    protected $primaryKey = 'id';
}
