<?php 

namespace digipos\Libraries;
use Request;

class Currency{
	private $data;
	public static function convert($value,$format){
                $currency_prefix = $format->currency_prefix;
                $currency_sufix = $format->currency_sufix;
                $conversion_scale = $format->conversion_scale;
                $decimal_count = $format->decimal_count;
                $decimal_separator = $format->decimal_separator;
                $data = $currency_prefix." ".number_format($value*$conversion_scale,$decimal_count,",",$decimal_separator).$currency_sufix;
                return $data;
	}
}