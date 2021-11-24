<?php

namespace digipos\Libraries;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class kyubi
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *  
     * @param  updateambulanceadmin  $event
     * @return void
     */
    public static function getDateIntervalToYearMontDay($dt1, $dt2)
    {
        $date1          = date_create(date_format(date_create($dt1),'Y-m-d'));
        $date2          = date_create(date_format(date_create($dt2),'Y-m-d'));
        $diff           = date_diff($date1,$date2);
        $interval_day   = $diff->format("%a");
        $y              = (int)($interval_day / 360);
        $m              = (int)(($interval_day - ($y * 360)) / 30);
        $d              = (int)(($interval_day - ($y * 360) - ($m * 30)));

        return $arr = [
            "year"  => $y,
            "month" => $m,
            "day" => $d,
            "interval_day" => $interval_day
        ];
    }

    public static function rupiah($angka){
    
        $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
        return $hasil_rupiah;

    }

     public static function decode_rupiah($price){
        if($price != 'Nan'){
            $price = str_replace(',', '', $price);
            $price = substr($price, 0, strpos($price, '.'));
            return $price;
        }else{
             return null;
        }
    }

    /*
        fucntion check string consist of alphabet and numeric
    */
    public static function check_alphanumeric($string){
        $alpha      = ctype_alpha($string);
        $num2       = is_numeric($string);
        // $string1 = "asd 123";
        // $string2 = "this is a string";
        // $regex      = preg_match('/[^a-z_\-0-9]/i', $string1);
        // $regex2      = preg_match('/[^a-z_\-0-9]/i', $string2);
        // $regex3      = preg_match( '/[^A-Za-z0-9]+/', '123@asd');
        // dd($alpha.'-'.$num.'-'.$num2.'-'.$regex.'-'.$regex2.'-'.$regex3);
        if($alpha == '' && $num2 == ''){
                return true;
        }else{
            return false;
        }
    }

     /*
        fucntion compare array, and get array different from new array or $arr1 from compare array or $arr2
    */
    public static function array_different($arr1, $arr2){
        $arr1 = [5,6,7];
        $arr2 = [5,6];
        $res = []; 
        
        foreach ($arr1 as $v) {
            if(!in_array($v, $arr2)){
                array_push($res, $v);
            }
        }
        var_dump($res);
        if($res){
                return $res;
        }else{
            return false;
        }
    }


    //fungsi untuk generate nominal menjadi terbilang
    public function kekata($x) {
        $x = abs($x);
        $angka = array("", "satu", "dua", "tiga", "empat", "lima",
        "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($x <12) {
            $temp = " ". $angka[$x];
        } else if ($x <20) {
            $temp = $this->kekata($x - 10). " belas";
        } else if ($x <100) {
            $temp = $this->kekata($x/10)." puluh". $this->kekata($x % 10);
        } else if ($x <200) {
            $temp = " seratus" . $this->kekata($x - 100);
        } else if ($x <1000) {
            $temp = $this->kekata($x/100) . " ratus" . $this->kekata($x % 100);
        } else if ($x <2000) {
            $temp = " seribu" . $this->kekata($x - 1000);
        } else if ($x <1000000) {
            $temp = $this->kekata($x/1000) . " ribu" . $this->kekata($x % 1000);
        } else if ($x <1000000000) {
            $temp = $this->kekata($x/1000000) . " juta" . $this->kekata($x % 1000000);
        } else if ($x <1000000000000) {
            $temp = $this->kekata($x/1000000000) . " milyar" . $this->kekata(fmod($x,1000000000));
        } else if ($x <1000000000000000) {
            $temp = $this->kekata($x/1000000000000) . " trilyun" . $this->kekata(fmod($x,1000000000000));
        }     
            return $temp;
    }

    //fungsi untuk get nominal terbilang dan ubah style tulisan
    public function terbilang($x, $style=4) {
        if($x<0) {
            $hasil = "minus ". trim($this->kekata($x));
        } else {
            $hasil = trim($this->kekata($x));
        }     
        switch ($style) {
            case 1:
                $hasil = strtoupper($hasil);
                break;
            case 2:
                $hasil = strtolower($hasil);
                break;
            case 3:
                $hasil = ucwords($hasil);
                break;
            default:
                $hasil = ucfirst($hasil);
                break;
        }     
        return $hasil;
    }
}
