<?php

namespace App\Models\Khanza;

use Illuminate\Database\Eloquent\Model;

class DetailPemberianObat extends Model
{
    public $timestamps = false;
    protected $connection = "second_db";
    protected $table = "detail_pemberian_obat";
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = ['tgl_perawatan','jam','no_rawat','kodae_brng','no_batch','no_faktur'];
    protected $fillable = [
        // "no_rawat",
        // "nama_bayar",
        // "besarppn",
        // "besar_bayar"
    ];
}
