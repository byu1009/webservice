<?php

namespace App\Http\Controllers\Radiologi;

use App\Helpers\AuthHelper;
use App\Http\Controllers\Controller;
use App\Models\IoStatuSehatImagingStudy;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class StorageController extends Controller
{
    function getSeriesByDate($date='now'){
        $filter=[
            'Level' => 'Study',
            'Query' => ['StudyDate' => date('Ymd',strtotime($date))]
        ];
        $resp = Http::post(config('services.orthanc.url') . '/tools/find', $filter);

        if (!$resp->successful()) {
            throw new \Exception('Orthanc find study gagal');
        }
        if(count($resp->json())<=0){
            return response()->json([
                'code' => 204,
                'message' => 'Data tidak ditemukan!',
                'data' => null,
                'token' => AuthHelper::genToken(),
            ]);
        }
        $data = [];
        foreach ($resp->json() as $key => $value) {
            $item =[];
            $item = Http::get(config('services.orthanc.url') . '/studies/'.$value);
            $data[]=$item->json();
        }
        return response()->json([
            'code' => ($data) ? 200 : 204,
            'message' => ($data) ? 'Ok' : 'Data tidak ditemukan!',
            'data' => ($data) ? $data : null,
            'url'=>config('services.orthanc.url') . '/tools/find',
            'query' => $filter,
            'token' => AuthHelper::genToken(),
        ]);

    }
    function setImagingStudy(){
        $request = request();
        $data = [
            'acsn'=>$request->acsn,
            'noorder'=>$request->noorder,
            'kd_jenis_prw'=>$request->kd_jenis_prw,
            'study_id'=>$request->study_id,
            'study_uuid'=>$request->acsn,
            'series_id'=>$request->series_id,
            'patient_id'=>$request->patient_id,
        ];

        $client = new Client([
            'base_uri' => config('services.orthanc.url'),
            'timeout' => 120,
        ]);
        $modify = $client->post("/studies/{$request->study_id}/modify", [
            'json' => [
                'Replace' => [
                    '0008,0050' => $request->acsn,
                    '0032,1032' => $request->requester ?? ''
                ],
                'Force' => true
            ]
        ]);

        $finalStudy = json_decode($modify->getBody(), true);
        if($finalStudy){
            $del=$client->delete("/studies/{$request->study_id}");
            if (!$del) {
            throw new \Exception("Gagal menghapus study {$request->study_id}");
        }
        }
        /* Ambil detail study */
        $studyInfo = json_decode(
            $client->get("/studies/{$finalStudy['ID']}")->getBody(),
            true
        );

        $studyUid = $studyInfo['MainDicomTags']['StudyInstanceUID'] ?? null;

        /* Ambil series */
        $seriesList = json_decode(
            $client->get("/studies/{$finalStudy['ID']}/series")->getBody(),
            true
        );

        if (empty($seriesList)) {
            return $this->error('Series tidak ditemukan pada study', 422);
        }

        $series = $seriesList[0];
        $imagingStudy = IoStatuSehatImagingStudy::find($request->acsn)
            ?? new IoStatuSehatImagingStudy();

        $imagingStudy->acsn = $request->acsn;
        $imagingStudy->noorder = $request->noorder;
        $imagingStudy->patient_id = $finalStudy['PatientID'];
        $imagingStudy->study_id = $finalStudy['ID'];
        $imagingStudy->series_id = $series['ID'];
        $imagingStudy->study_uid = $studyUid;
        $imagingStudy->kd_jenis_prw = $request->kd_jenis_prw;
        $imagingStudy->save();

        $series = $seriesList[0];
        return response()->json([
            'code' =>  200 ,
            'message' =>  'Ok' ,
            'data' => $imagingStudy,
            'request' => $data,
        ]);
    }

    private function error(string $message, int $code = 500, $data = null)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
