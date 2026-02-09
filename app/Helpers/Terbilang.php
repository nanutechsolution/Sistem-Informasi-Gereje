<?php

namespace App\Helpers;

class Terbilang
{
    public static function make($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        
        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
            $temp = self::make($nilai - 10). " Belas";
        } else if ($nilai < 100) {
            $temp = self::make($nilai/10)." Puluh". self::make($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " Seratus" . self::make($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = self::make($nilai/100) . " Ratus" . self::make($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " Seribu" . self::make($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = self::make($nilai/1000) . " Ribu" . self::make($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = self::make($nilai/1000000) . " Juta" . self::make($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = self::make($nilai/1000000000) . " Milyar" . self::make(fmod($nilai,1000000000));
        }
        return $temp;
    }
}