<?php 

namespace digipos\Libraries;
use Request;
use Excel;
use Auth;

class Report{
	private static $data;
    private static $title;
    private static $type;
    private static $view;
    private static $format;
    private static $creator;

    public static function setformat($format){
        Self::$format   = $format;
    }

    public static function setdata($data){
        Self::$data     = $data;
    }

    public static function settitle($title){
        Self::$title    = $title;
    }

    public static function settype($type){
        Self::$type     = $type;
    }

    public static function setview($view){
         Self::$view    = $view;
    }

    public static function setcreator($creator){
        Self::$creator  = $creator;
    }

    public static function generate(){
        $type = Self::$type;
        if($type == 'excel'){
            return Self::excel();
        }else{
            return Self::pdf();
        }
    }

    public static function excel(){
        $title      = Self::$title;
        $data       = Self::$data;
        $view       = Self::$view;
        $format     = Self::$format;
        $creator    = Self::$creator;
        Excel::create($title, function($excel) use($title,$data,$creator,$view,$format){
            $excel->setTitle($title);
            $excel->setCreator($creator)
                  ->setCompany('Outpost')
                  ->setDescription($title);
            $excel->sheet($title,function($sheet) use($view,$data){
                $sheet->loadView($view,['data' => $data]);
            });
        })->export($format);
    }
}