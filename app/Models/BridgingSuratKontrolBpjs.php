<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BridgingSuratKontrolBpjs extends Model
{
    public $timestamps = false;
    protected $table = "bridging_surat_kontrol_bpjs";
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = ['no_surat'];

    protected $fillable = [
        'no_sep',
        'tgl_surat',
        'no_surat',
        'tgl_rencana',
        'kd_dokter_bpjs',
        'nm_dokter_bpjs',
        'kd_poli_bpjs',
        'nm_poli_bpjs'
    ];
    public function sepAsal()
    {
        return $this->hasOne(BridgingSep::class,'no_sep','no_sep')
        ->with(['pasien'])
        ->select([
            'no_sep',
            'noskdp',
            'no_kartu',
            'no_rawat',
            'nama_pasien',
            'nomr',
            'nmdiagnosaawal'
        ]);
    }
}
