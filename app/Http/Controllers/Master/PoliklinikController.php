<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Poliklinik;
use Illuminate\Http\Request;

class PoliklinikController extends Controller
{
    public function index()
    {
        $data = Poliklinik::where('kd_poli','like','%PD%')->get();
        return response()->json([
            'code' => 200, 
            'data' => $data,
            'message' => 'Success',
        ]);
    }
    public function store(Request $request)
    {
        $model = new Poliklinik();
        $model->kd_poli = $request->input('kd_poli');
        $model->nm_poli = $request->input('nm_poli');
        $model->registrasi = $request->input('registrasi');
        $model->registrasilama = $request->input('registrasilama');
        $model->status = $request->input('status');
        $model->save();
        return response()->json([
            'code' => 200, 
            'data' => $model,
            'message' => 'Success',
        ]);
    }
}
