<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index()
    {
        $data = Pasien::where('no_rkm_medis', '000001')->get();
        return response()->json([
            'code' => 200,
            'data' => $data,
            'message' => 'Success',
        ]);
    }
}
