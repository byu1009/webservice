<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Emr\DataKlinis\RiwayatController;
use App\Http\Controllers\Farmasi\ResepController;
use App\Http\Controllers\Jkn\JknApiAntrolController;
use App\Http\Controllers\Jkn\JknSuratkontrolController;
use App\Http\Controllers\Jkn\JknTaskidController;
use App\Http\Controllers\Master\JadwalPraktekController;
use App\Http\Controllers\Master\DashboardController;
use App\Http\Controllers\Master\PasienController;
use App\Http\Controllers\Master\DokterController;
use App\Http\Controllers\Master\PoliklinikController;
use App\Http\Controllers\Master\ReferensiController;
use App\Http\Controllers\Radiologi\PermintaanController;
use App\Http\Controllers\Monev\MonevController;
use App\Http\Controllers\Radiologi\StorageController;
use App\Http\Controllers\Rajal\Antrian\AntrianRJController;
use App\Http\Controllers\Rajal\Antrian\DashboardRjController;
use App\Http\Controllers\Registrasi\RegistrasiController;
use App\Http\Controllers\Service\ServiceFarmasiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login',  [AuthController::class, 'login']);
Route::post('/auth/check-username',  [AuthController::class, 'checkUsername']);
Route::post('/check',  [AuthController::class, 'check']);
Route::get('/auth/login-data',   [AuthController::class, 'loginData']);

Route::middleware(['api_token'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'index');
    });

    // START MASTER
    Route::controller(PasienController::class)->group(function () {
        Route::get('/pasien/getdata', 'getdata');
        Route::post('/pasien/search', 'searchPasien');
        Route::post('/pasien/create', 'createPasien');
        Route::post('/pasien/destroy', 'destroy');
    });

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard/getdata', 'getdata');
        Route::post('/dashboard/postdata', 'postdata');
        Route::post('/dashboard/updatedata', 'updatedata');
        Route::post('/dashboard/hapusdata', 'hapusdata');

        Route::get('/dashboard/detaildata', 'getdetail');
        Route::post('/dashboard/detailpost', 'postdetail');
        Route::post('/dashboard/detailupdate', 'updatedetail');
        Route::post('/dashboard/detailhapus', 'hapusdetail');

        Route::get('/dashboard/datacontrol', 'datacontrol');
        Route::post('/dashboard/updatecontrol', 'updatecontrol');

        Route::get('/dashboard/reg/jenis-antrian', 'refJenisAntrian');
        Route::get('/dashboard/reg/dashboard-parent', 'refParent');
    });

    Route::controller(ReferensiController::class)->group(function () {
        Route::get('/ref/penjab', 'penjab');
        Route::post('/ref/kelurahan', 'kelurahan');
        Route::post('/ref/kecamatan', 'kecamatan');
        Route::post('/ref/kabupaten', 'kabupaten');
        Route::get('/ref/perusahaan-pasien', 'perusahaanpasien');
        Route::get('/ref/suku-bangsa', 'sukubangsa');
        Route::get('/ref/bahasa-pasien', 'bahasapasien');
        Route::get('/ref/cacat-fisik', 'cacatfisik');
        Route::get('/ref/propinsi', 'propinsi');
        Route::get('/ref/provinsi', 'provinsi');
        Route::post('/ref/ambil-wilayah', 'getWilayah');
    });
    // END MASTER

    // START JKN
    Route::controller(JknSuratkontrolController::class)->group(function () {
        Route::post('/jkn/surkon/getdata', 'getdata');
        Route::post('/jkn/surkon/get-jadwal', 'getPlan');
    });

    Route::controller(JknTaskidController::class)->group(function () {
        Route::post('/jkn/taskid/post', 'post');
        Route::post('/jkn/taskid/data', 'getdata');
        Route::post('/jkn/taskid/send', 'send');
    });
    // END JKN

    // START REGISTRASI
    Route::controller(RegistrasiController::class)->group(function () {
        Route::post('/registrasi/get-rajal', 'getDataRajal');
        Route::post('/registrasi/getdata', 'getdata');
        Route::post('/registrasi/post', 'post');
        Route::post('/registrasi/add-antrol', 'addantrian');
        Route::post('/registrasi/batal-periksa', 'batalPeriksa');
        Route::post('/registrasi/add-antrol-farmasi', 'addAntrianFarmasi');
        Route::post('/registrasi/checkin', 'checkin');
    });
    // END REGISTRASI

    // START EMR
    Route::controller(RiwayatController::class)->group(function () {
        Route::post('/emr/data-klinis/riwayat/getdata', 'getdata');
        Route::post('/emr/data-klinis/riwayat/soap', 'soapie');
        Route::post('/emr/data-klinis/riwayat/soap/multi', 'soapiemulti');
        Route::post('/emr/data-klinis/riwayat/sepbpjs', 'datasep');
        Route::post('/emr/data-klinis/riwayat/awal/medis', 'awalMedis');
        Route::post('/emr/data-klinis/riwayat/awal/keperawatan', 'awalKeperawatan');
        Route::post('/emr/data-klinis/riwayat/diagnosa-icd10', 'diagnosaIcd10');
        Route::post('/emr/data-klinis/riwayat/tindakan/dokter/rajal', 'tindakanDokterRajal');
        Route::post('/emr/data-klinis/riwayat/detail-pemberian-obat', 'detailPemberianObat');
    });
    // END EMR

    // START PELAYANAN RAJAL
    Route::controller(AntrianRJController::class)->group(function () {
        Route::get('rajal/antrian/jadwal', 'jadwalPoli');
        Route::post('rajal/antrian/periksa', 'antrianPeriksa');
        Route::post('rajal/antrian/skip', 'antrianSkip');
        Route::post('rajal/antrian/panggil', 'antrianPanggil');
        Route::post('rajal/antrian/masuk', 'antrianMasuk');
        Route::post('rajal/antrian/selesai', 'antrianSelesai');
    });

    Route::controller(DashboardRjController::class)->group(function () {
        Route::get('/rajal/dashboard/view/{id}', 'view');
        Route::get('/rajal/dashboard/panggil/{id}', 'panggil');
    });
    // END PELAYANAN RAJAL

    // START FARMASI
    Route::controller(ResepController::class)->group(function () {
        Route::post('/resep/getdata', 'resepGetdata');
        Route::post('/resep/selesai', 'resepSelesai');
    });
    // END FARMASI

    Route::prefix('radiologi')->group(function () {
        Route::controller(PermintaanController::class)->group(function () {
            Route::post('/permintaan', 'getdata');
        });
    });

    Route::get('/master/poliklinik',  [PoliklinikController::class, 'index']);
    Route::post('/master/poliklinik',  [PoliklinikController::class, 'store']);
    Route::get('/master/dokter',  [DokterController::class, 'index']);
    Route::post('/master/dokter',  [DokterController::class, 'store']);
});

// START API ANTROL
Route::controller(JknApiAntrolController::class)->group(function () {
    Route::get('/api-antrol/ref/poli', 'refPoli');
    Route::get('/api-antrol/ref/dokter', 'refDokter');
    Route::post('/api-antrol/ref/jadwal-dokter', 'refJadwalDokter');
    Route::get('/api-antrol/ref/poli-fp', 'refPoliFP');
    Route::post('/api-antrol/ref/pasien-fp', 'refPasienFP');
    Route::post('/api-antrol/antrian-tanggal', 'antrianPerTgl');
    Route::post('/api-antrol/antrian-nobooking', 'antrianPerKbo');
    Route::get('/api-antrol/antrian-aktif', 'antrianAktif');
    Route::post('/api-antrol/antrian-nobooking-detail', 'antrianAktifDetail');
    Route::post('/api-antrol/antrian/taskid', 'listTaskid');
    Route::post('/api-antrol/antrian/updatewaktu', 'updateWaktuAntrian');
    Route::post('/api-antrol/daftar/antrian', 'daftarAntrian');
    Route::post('/api-antrol/daftar/antrian/farmasi', 'daftarAntrianFarmasi');
    Route::post('/api-antrol/batal/antrian', 'batalAntrean');
});
// END API ANTROL

// START SERVICE
Route::controller(ServiceFarmasiController::class)->group(function () {
    Route::get('/service/farmasi/antrian-tambah', 'antrianTambah');
});
// END SERVICE

// BYPASS
Route::controller(MonevController::class)->group(function () {
    Route::get('monev', 'index')->name('monev');

    Route::get('monev/antrian-terdaftar', 'antrolTerdaftar')->name('monev.antrolterdaftar');
    Route::post('monev/antrian-terdaftar/api', 'antrolTerdaftarApi')->name('monev.antrolterdaftar.api');
    Route::post('monev/antrian-terdaftar/batal', 'antrolTerdaftarBatal')->name('monev.antrolterdaftar.batal');
});
    Route::get('/master/jadwal-praktek/tanggal',  [JadwalPraktekController::class, 'getByDate']);
    Route::get('/master/jadwal-praktek/tanggal/{date}',  [JadwalPraktekController::class, 'getByDate']);
    Route::get('/radiologi/permintaan',  [PermintaanController::class, 'getData']);





use App\Http\Controllers\SatuSehat\AuthController as SatuSehatAuthController;
use App\Http\Controllers\SatuSehat\PatientController as SatuSehatPatientController;
use App\Http\Controllers\SatuSehat\PractitionerController as SatuSehatPractitionerController;
use App\Http\Controllers\SatuSehat\LokasiRalanController;
use App\Http\Controllers\SatuSehat\EncounterController;
use App\Http\Controllers\SatuSehat\RadiologiRequestController;
use App\Http\Controllers\SatuSehat\ImagingStudiesController;
use App\Http\Controllers\Radiologi\WorklistController;

    Route::get('/phpinfo',  function () {phpinfo();});
    Route::get('/satu-sehat/auth',  [SatuSehatAuthController::class, 'auth']);
    Route::get('/satu-sehat/patient/{nik}',  [SatuSehatPatientController::class, 'getOne']);
    Route::get('/satu-sehat/practitioner/{nik}',  [SatuSehatPractitionerController::class, 'getOne']);
    Route::get('/satu-sehat/lokasi-ralan/{nik}',  [LokasiRalanController::class, 'getOne']);
    Route::post('/satu-sehat/encounter', action: [EncounterController::class, 'store']);
    Route::post('/satu-sehat/radiologi/service-request',  [RadiologiRequestController::class, 'store']);
    Route::put('/satu-sehat/radiologi/service-request',  [RadiologiRequestController::class, 'update']);
    Route::post('/satu-sehat/radiologi/imaging-study/upload',  [ImagingStudiesController::class, 'upload']);
    Route::post('/satu-sehat/radiologi/imaging-study/upload/{acsn}', [ImagingStudiesController::class, 'upload']);
    Route::delete('/satu-sehat/radiologi/imaging-study/delete/{study_id}', [ImagingStudiesController::class, 'delete']);
    Route::post('/satu-sehat/radiologi/imaging-study/send-to-modality/{modality}',  [ImagingStudiesController::class, 'sendToModality']);
    Route::get('/satu-sehat/radiologi/imaging-study/retrieve/{acsn}',  [ImagingStudiesController::class, 'getCloudData']);
    
    Route::get('/radiologi/worklist/generate',  [WorklistController::class, 'generate']);
    Route::get('/radiologi/worklist/generate/{date}',  [WorklistController::class, 'generate']);
    Route::get('/radiologi/storage/get-study-at/{date}',  [StorageController::class, 'getSeriesByDate']);
    Route::post('/radiologi/storage/set-imaging-study',  [StorageController::class, 'setImagingStudy']);
