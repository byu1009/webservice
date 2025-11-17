<?php

namespace App\Models\Khanza;

use Illuminate\Database\Eloquent\Model;

class RegPeriksaModel extends Model
{
    protected $connection = "second_db";
    protected $table = "reg_periksa";
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $primaryKey = 'no_rawat';
}
