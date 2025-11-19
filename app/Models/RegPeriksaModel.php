<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegPeriksaModel extends Model
{
    protected $table = "reg_periksa";
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $primaryKey = 'no_rawat';

    protected $fillable = [
        'no_reg',
        'no_rawat',
        'tgl_registrasi',
        'jam_reg',
        'kd_dokter',
        'no_rkm_medis',
        'kd_poli',
        'p_jawab',
        'almt_pj',
        'hubunganpj',
        'biaya_reg',
        'stts',
        'stts_daftar',
        'status_lanjut',
        'kd_pj',
        'umurdaftar',
        'sttsumur',
        'status_bayar',
        'status_poli'
    ];
}
