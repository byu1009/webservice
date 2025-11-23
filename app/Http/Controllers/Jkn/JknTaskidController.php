<?php

namespace App\Http\Controllers\Jkn;

use App\Helpers\AuthHelper;
use App\Http\Controllers\Controller;
use App\Models\IoAntrianTaskid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JknTaskidController extends Controller
{
    public function post(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nobooking' => 'required',
                'taskid'    => 'required|in:1,2,3,4,5,6,7,99',
                'waktu'     => 'required',
            ],
            [
                'nobooking.required' => 'Nobooking tidak boleh kosong',
                'taskid.required'    => 'Taskid tidak boleh kosong',
                'taskid.in'          => 'Taskid tidak berlaku',
                'waktu.required'     => 'Waktu tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'code'    => 204,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        $taskid = (int) $request->taskid;
        $kolom = "taskid_{$taskid}";
        $kolomSend = "taskid_{$taskid}_send";

        // Validasi dasar
        $validator = Validator::make($request->all(), [
            'nobooking' => 'required',
            'taskid'    => 'required|in:3,4,5,6,7,99',
            'waktu'     => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code'    => 204,
                'message' => $validator->errors()->first()
            ]);
        }

        // Ambil/insert data dasar
        $task = IoAntrianTaskid::firstOrCreate([
            'nobooking' => $request->nobooking
        ]);

        /* ============================================================
            RULE 1: Jika taskid_99 sudah terisi → semuanya diblokir
        ============================================================= */
        if ($taskid !== 99 && !empty($task->taskid_99)) {
            return response()->json([
                'code'    => 201,
                'message' => "Tidak dapat memproses TaskID {$taskid}. TaskID 99 telah mengunci proses."
            ]);
        }

        /* ============================================================
            RULE 2: Validasi khusus TaskID 99
        ============================================================= */
        if ($taskid == 99) {

            // TaskID 3 harus sudah terisi
            if (empty($task->taskid_3)) {
                return response()->json([
                    'code'    => 201,
                    'message' => "TaskID 99 tidak dapat diproses sebelum TaskID 3 terisi."
                ]);
            }

            // TaskID 5 tidak boleh terisi
            if (!empty($task->taskid_5)) {
                return response()->json([
                    'code'    => 201,
                    'message' => "TaskID 99 tidak dapat diproses karena TaskID 5 sudah terisi."
                ]);
            }

            // Tidak bisa update jika sudah terkirim
            if (!empty($task->taskid_99_send)) {
                return response()->json([
                    'code'    => 201,
                    'message' => "TaskID 99 tidak dapat diupdate karena sudah terkirim."
                ]);
            }

            // Update taskid_99
            $task->update([$kolom => $request->waktu]);

            return response()->json([
                'code'    => 200,
                'message' => "TaskID 99 berhasil disimpan.",
                'token'   => AuthHelper::genToken()
            ]);
        }

        /* ============================================================
            RULE 3: Validasi berurutan untuk taskid 3–7
        ============================================================= */
        $flow = [3, 4, 5, 6, 7];
        $pos  = array_search($taskid, $flow);

        // Jika bukan task pertama (3), harus ada task sebelumnya
        if ($taskid !== 3) {
            $prevTask = $flow[$pos - 1];
            $kolomPrev = "taskid_{$prevTask}";

            // Harus berurutan
            if (empty($task->$kolomPrev)) {
                return response()->json([
                    'code'    => 201,
                    'message' => "TaskID {$taskid} tidak dapat diproses sebelum TaskID {$prevTask} terisi."
                ]);
            }

            // Waktu tidak boleh mundur
            if (strtotime($request->waktu) < strtotime($task->$kolomPrev)) {
                return response()->json([
                    'code'    => 201,
                    'message' => "Waktu TaskID {$taskid} tidak boleh lebih kecil dari TaskID {$prevTask}."
                ]);
            }
        }

        /* ============================================================
            RULE 4: Tidak boleh update jika taskid_x_send sudah terisi
        ============================================================= */
        if (!empty($task->$kolomSend)) {
            return response()->json([
                'code'    => 201,
                'message' => "TaskID {$taskid} tidak dapat diupdate karena sudah terkirim."
            ]);
        }

        /* ============================================================
            RULE 5: TaskID_x boleh diupdate selama belum terkirim
        ============================================================= */
        $task->update([
            $kolom => $request->waktu
        ]);

        return response()->json([
            'code'    => 200,
            'message' => "TaskID {$taskid} berhasil disimpan.",
            'token'   => AuthHelper::genToken()
        ]);
    }
}
