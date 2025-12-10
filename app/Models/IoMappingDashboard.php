<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IoMappingDashboard extends Model
{
    public $timestamps = false;
    protected $table = "io_mapping_dashboard";
    public $incrementing = true;
    protected $keyType = 'int';
    protected $primaryKey = 'dash_id';
    protected $fillable = [
        'dash_nama',
        'dash_status'
    ];
}
