<?php

namespace App\Http\Controllers\Master;

use App\Helpers\AuthHelper;
use App\Helpers\BPer;
use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class JadwalPraktekController extends Controller
{
    public function index()
    {
        $data = Jadwal::all();
        return response()->json([
            'code' => 200, 
            'data' => $data,
            'message' => 'Success',
            'token' => AuthHelper::genToken(),
        ]);
    }
    public function getByDate($date='now'){
        $date = date('Y-m-d', strtotime($date));
        $hari = strtoupper(
            Carbon::parse($date)->locale('id')->isoFormat('dddd')
        );
        $data = Jadwal::with(['dokter', 'poli'])
        ->leftJoin('reg_periksa', function ($join) use ($date) {
            $join->on('reg_periksa.kd_dokter', '=', 'jadwal.kd_dokter')
                ->on('reg_periksa.kd_poli', '=', 'jadwal.kd_poli')
                ->whereDate('reg_periksa.tgl_registrasi', $date);
        })
        ->where('jadwal.hari_kerja','=', $hari)
        ->where('jadwal.kd_poli','!=', 'IGDK')
        ->select([
            'jadwal.hari_kerja',
            'jadwal.kd_poli',
            'jadwal.kd_dokter',
            'jadwal.jam_mulai',
            'jadwal.jam_selesai',
            'jadwal.kuota',
            DB::raw('COUNT(reg_periksa.kd_poli) as total_registrasi'),
            DB::raw('(jadwal.kuota - COUNT(reg_periksa.kd_poli)) as sisa'),
        ])
        ->groupBy(
            'jadwal.hari_kerja',
            'jadwal.kd_poli',
            'jadwal.kd_dokter',
            'jadwal.jam_mulai',
            'jadwal.jam_selesai',
            'jadwal.kuota'
        )
        ->get();
        return response()->json([
            'code' => 200, 
            'data' => $data,
            'message' => 'Success',
            'token' => AuthHelper::genToken(),
        ]);
    }
    public function store(Request $request)
    {
        $model = new Jadwal();
        $model->save();
        return response()->json([
            'code' => 200, 
            'data' => $model,
            'message' => 'Success',
            'token' => AuthHelper::genToken(),
        ]);
    }
}
