<?php

namespace App\Http\Controllers\Farmasi;

use App\Helpers\AuthHelper;
use App\Helpers\BPer;
use App\Http\Controllers\Controller;
use App\Models\IoAntrianTaskid;
use App\Models\ResepObat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResepController extends Controller
{
    public function resepGetdata(Request $request)
    {
        $rules = [
            'tanggalawal'   => 'required|string',
            'tanggalakhir'   => 'required|string',
        ];

        $messages = [
            'required'  => ':attribute tidak boleh kosong',
            'string'    => ':attribute harus berupa string',
            'int'       => ':attribute harus berupa integer',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'code'    => 201,
                'message' => $validator->errors()->first()
            ]);
        }

        $tglAwal = $request->tanggalawal;
        $tglAkhir = $request->tanggalakhir;

        $find = ResepObat::whereBetween('tgl_perawatan', [$tglAkhir, $tglAwal])
                    ->where('resep_obat.status', 'ralan')
                    ->join('io_referensi_farmasi', 'resep_obat.no_resep', '=', 'io_referensi_farmasi.no_resep')
                    ->orderBy('resep_obat.no_resep', 'ASC')
                    ->get();

        if ($find && $find->isEmpty()) {
            return response()->json([
                'code'    => 201,
                'message' => 'Data resep kosong'
            ]);
        }

        return response()->json([
            'code'    => 200,
            'message' => 'Data resep ditemukan',
            'data'    => $find,
            'token'   => AuthHelper::genToken()
        ]);
    }

    public function resepSelesai(Request $request)
    {
        $rules = [
            'noresep'   => 'required|string',
        ];

        $messages = [
            'required'  => ':attribute tidak boleh kosong',
            'string'    => ':attribute harus berupa string',
            'int'       => ':attribute harus berupa integer',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'code'    => 201,
                'message' => $validator->errors()->first()
            ]);
        }

        $find = ResepObat::where('no_resep', $request->noresep)
                    ->where('status', 'ralan')
                    ->first();

        if ($find && empty($find)) {
            return response()->json([
                'code'    => 201,
                'message' => 'Data resep tidak ditemukan'
            ]);
        }

        // kirim taskid 6
        $taskid = IoAntrianTaskid::where('nobooking', BPer::cekNoRef($find->no_rawat))->first();

        return $taskid;
    }
}
