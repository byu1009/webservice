<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IoMappingDashboardDetail extends Model
{
    public $timestamps = false;
    protected $table = "io_mapping_dashboard_detail";
    public $incrementing = true;
    protected $keyType = 'int';
    protected $primaryKey = 'dashd_id';
    protected $fillable = [
        'dashd_parent',
        'dashd_unit',
        'dashd_status'
    ];
}
