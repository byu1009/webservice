<?php

namespace App\Helpers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;

class BPer
{
    public static function validTanggal($tanggal)
    {
        $format = 'Y-m-d';
        $d = DateTime::createFromFormat($format, $tanggal);

        return $d && $d->format($format) === $tanggal;
    }

    public static function tebakHari($tanggal)
    {
        $carbonDate = Carbon::parse($tanggal);

        // Mendapatkan nomor hari (0=Minggu, 1=Senin, ..., 6=Sabtu)
        $dayNumber = $carbonDate->dayOfWeek;

        $hariIndonesia = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $namaHari = Str::upper($hariIndonesia[$dayNumber]);

        return $namaHari;
    }

    public static function hitungUmur($tanggal_lahir)
    {
        $lahir = Carbon::parse($tanggal_lahir);
        $now   = Carbon::now();

        $diff = $lahir->diff($now);

        if ($diff->y >= 1) {
            // Jika sudah 1 tahun atau lebih → tahun
            return $diff->y . ' Th';
        } elseif ($diff->m >= 1) {
            // Jika kurang dari 1 tahun tapi sudah 1 bulan → bulan
            return $diff->m . ' Bl';
        } else {
            // Jika kurang dari 1 bulan → hari
            return $diff->d . ' Hr';
        }
    }
}
