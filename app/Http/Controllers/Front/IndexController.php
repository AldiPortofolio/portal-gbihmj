<?php namespace digipos\Http\Controllers\Front;

use digipos\models\Front_menu;
use digipos\models\Mslanguage;
use digipos\models\Front_seo_setting;
use digipos\models\Front_slideshow;
use digipos\models\Country;
use digipos\models\Modem;
use digipos\models\Front_section;

use DB;
use FeedReader;
use Illuminate\Http\Request;

class IndexController extends ShukakuController {

	public function __construct(){
		parent::__construct();
		$this->data['header_info']	= 'Home';
		$this->menu 				= $this->data['path'][0];
		$this->data['menu'] 		= $this->menu;
		$this->data['path'] 		= 'cek-alamat';
		$this->data['search'] 		= '';

		$this->image_path 			= 'components/both/images/';
		$this->data['image_path'] 	= $this->image_path;

		$this->model 				= new Front_menu;
		$this->seo_setting 			= new Front_seo_setting;
		$this->mslanguage 			= new Mslanguage;
		// $this->curr_lang 			= $this->getSetLang('');
		// $this->data['curr_lang']    = $this->curr_lang;
		/*meta tag*/
		// $this->data['meta_title'] 				= $this->menu;		
		// $this->data['meta_description'] 		= $this->menu;		
		// $this->data['meta_keyword'] 			= $this->menu;		
		/*end meta tag*/
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	// public function lang($id_lang){
 //        // dd($request->langs_box);
 //        $ids = $id_lang;
 //        $default = Mslanguage::first();
 //        $get_lang = session('lang');

 //        if($ids != NULL || $get_lang != NULL) {
 //            if($ids != NULL) {
 //                session(['lang' => $ids]);
 //                $lang = $ids;
 //            } else{
 //                $lang = $get_lang;
 //            }
 //        } else{
 //            $lang = $default->id;
 //        }

 //        return $lang;
 //    }

    public function set_lang(request $request){
    	$id_lang = $request->langs_box;
    	$lang = $this->getSetLang($id_lang);
    	return $this->render_view('pages.index');
    }

    // public function lang($ids){
    //     // dd($request->langs_box);
    //     $default = Mslanguage::first();
    //     $get_lang = session('lang');

    //     if($ids != NULL || $get_lang != NULL) {
    //         if($ids != NULL) {
    //             session(['lang' => $ids]);
    //             $lang = $ids;
    //         } else{
    //             $lang = $get_lang;
    //         }
    //     } else{
    //         $lang = $default->id;
    //     }

    //     return $lang;
    // }


    public function GenerateDefault($lg){
        // dd($lg);
        // $lang = Session::get('lang');
        // $this->view_path = 'admin';
        // $lg = Setting::select('value')->where('option', 'lang')->first();
        $this->data['menus'] = Front_menu::where([['id_lang', $lg],['status', 'y']])->get();
        // dd($this->data['menu']);
        // $this->data['lg'] = $lg;
    }


	public function index(request $request){
		return 'under development';
		$ids 	= ($request->langs_box == NULL ? 1 : $request->langs_box);
        $lg = $this->getSetLang($ids);
        $this->GenerateDefault($lg);
        $default_menu 					= Front_menu::where([['id_lang',1],['heading','home']])->first();
		$this->data['menu'] 			= Front_menu::where([['id_lang',$ids],['heading','home']])->first();
		// $this->data['lang'] 			= $this->mslanguage->get();

		$this->data['slide'] 		= Front_slideshow::where('status','y')->get();
		// dd($this->data['slide']);
		  // $feed = FeedReader::read('https://www.satudigital.com/blog/feed/');
		  // $feed = FeedReader::read('http://simplepie.org/blog/feed/');
		// $feed = FeedReader::read('http://digg.com/');
		// $this->data['adbooth_1'] = Home::where('id', 1)->first();
		// $this->data['adbooth_2'] = Home::where('id', 2)->first();
	    // $data = array(
	    //   'title'     => $feed->get_title(),
	    //   'permalink' => $feed->get_permalink(),
	    //   'items'     => $feed->get_items(),
	    // );

	    // $feed->handle_content_type();
	    // $feed->get_items(0, 5);
	    // $this->data['feed'] 		= $feed;
	    // $this->data['custom_err'] 	= $request->session()->get('custom_err');
	    // if($request->session()->get('custom_err')){
	    // 	 $request->session()->forget('custom_err');    
	    // }
	    // var_dump($page = $request->input('page'));
	    // dd($this->curr_lang);
	    $this->data['curr_lang'] = $this->getSetLang('');
	    $this->data['country'] = Country::where('status','y')->get();
	    $this->data['feature'] = Front_section::where([['status','y'],['id_lang', $ids],['parent',$default_menu->id]])->get();
	    // dd($this->data['feature']);
	    // var_dump('lang: '.$this->data['curr_lang']);
	    // dd($this->data['curr_lang']);
		return $this->render_view('pages.index');
	}

	public function cek_alamat(request $request){
		$search = $request->search;

		$this->data['data'] = Postcode::join('rgn_subdistrict', 'rgn_subdistrict.id', 'postcode.subdistrict_id')->join('rgn_district', 'rgn_district.id', 'postcode.district_id')->join('city', 'city.id', 'postcode.city_id')->join('province', 'province.id', 'postcode.province_id')->select('postcode.*', 'rgn_subdistrict.name as subdistrict_name', 'rgn_district.name as district_name', 'city.name as city_name', 'province.name as province_name')->orWhere('postcode', 'like', '%' . $search . '%')->orWhere('rgn_subdistrict.name', 'like', '%' . $search . '%')->orWhere('rgn_district.name', 'like', '%' . $search . '%')->orWhere('city.name', 'like', '%' . $search . '%')->orWhere('province.name', 'like', '%' . $search . '%')->paginate(20);
		// dd($this->data['data']);
		// if(count($this->data['data']) < 1){
		// 	$this->data['err'] = 'Tidak ada hasil untuk pencarian '.$search.'<br> Untuk hasil yang akurat, Ketikkan NAMA saja, tanpa awalan kata desa, kota, jalan, dsb';	
		// }

		$title = 'Kode Pos Indonesia';
		if(count($this->data['data'])){
			$title = "Halaman Tidak Ditemukan";
		}
		$this->data['meta'] 		= ['title' => $title, 'keywords' => 'Pos Indonesia', 'description' => 'Pos Indonesia'];
		$this->data['header'] 		= '';
		$this->data['meta_robots'] 	= 'noindex';
		$this->data['search'] 		= $search;
		return $this->render_view('pages.pages.cek_alamat');
	}

	public function postcode(request $request){
		$path = $this->data['path2'];
		// dd($this->data['path2']);
		// $path = explode('/',Request::path());
		// dd($path);
		$meta_title 	= '';
		$meta_keyword 	= 'Pos Indonesia';
		$meta_description 	= '';
		// if(count($path) == 6){
		// 	$cek_alamat = Postcode::join('rgn_subdistrict', 'rgn_subdistrict.id', 'postcode.subdistrict_id')->join('rgn_district', 'rgn_district.id', 'postcode.district_id')->join('city', 'city.id', 'postcode.city_id')->join('province', 'province.id', 'postcode.province_id')->select('postcode.*', 'rgn_subdistrict.name as subdistrict_name', 'rgn_district.name as district_name', 'city.name as city_name', 'province.name as province_name')->Where('postcode', 'like', '%' . $path[5] . '%');
		// }
		if(count($path) == 5){ 
			// dd($path);
			$canonical = $path[0].'/'.$path[1].'/'.$path[2].'/'.$path[3].'/'.$path[4];
			$kelurahan = str_replace('-', ' ', $path[4]);
			$kecamatan = str_replace('-', ' ', $path[3]);
			$kab_kota = str_replace('-', ' ', $path[2]);
			$provinsi = str_replace('-', ' ', $path[1]);
			$meta_title 		= ucwords('Kode Pos Kelurahan '.$kelurahan);
			$meta_description 	= ucwords('Berikut Kode Pos Kelurahan '.$kelurahan.' Provinsi '.$provinsi.' Kota '.$kab_kota.' Kecamatan '.$kecamatan);
			$this->data['header'] 	= ucwords('Kode Pos Kelurahan '.$kelurahan.' - Provinsi '.$provinsi.' - Kota '.$kab_kota.' - Kecamatan '.$kecamatan);
			$cek_alamat = Postcode::join('rgn_subdistrict', 'rgn_subdistrict.id', 'postcode.subdistrict_id')->join('rgn_district', 'rgn_district.id', 'postcode.district_id')->join('city', 'city.id', 'postcode.city_id')->join('province', 'province.id', 'postcode.province_id')->select('postcode.*', 'rgn_subdistrict.name as subdistrict_name', 'rgn_district.name as district_name', 'city.name as city_name', 'province.name as province_name')->Where([['rgn_subdistrict.name', 'like', '%' . $kelurahan . '%'],['rgn_district.name', 'like', '%' .$kecamatan. '%'],['city.name', 'like', '%' . $kab_kota . '%'],['province.name', 'like', '%' . $provinsi . '%']]);

		}
		elseif(count($path) == 4){
			$canonical = $path[0].'/'.$path[1].'/'.$path[2].'/'.$path[3];
			$kecamatan = str_replace('-', ' ', $path[3]);
			$kab_kota = str_replace('-', ' ', $path[2]);
			$provinsi = str_replace('-', ' ', $path[1]);
			$meta_title 		= ucwords('Kode Pos Kecamatan '.$kecamatan);
			$meta_description 	= ucwords('Berikut Kode Pos Kecamatan '.$kecamatan.' Kota '.$kab_kota.' Provinsi '.$provinsi);
			$this->data['header'] 	= ucwords('Kode Pos Kecamatan '.$kecamatan.' - Kota '.$kab_kota. ' - Provinsi '.$provinsi);
			$cek_alamat = Postcode::join('rgn_subdistrict', 'rgn_subdistrict.id', 'postcode.subdistrict_id')->join('rgn_district', 'rgn_district.id', 'postcode.district_id')->join('city', 'city.id', 'postcode.city_id')->join('province', 'province.id', 'postcode.province_id')->select('postcode.*', 'rgn_subdistrict.name as subdistrict_name', 'rgn_district.name as district_name', 'city.name as city_name', 'province.name as province_name')->Where([['rgn_district.name', 'like', '%' .$kecamatan. '%'],['city.name', 'like', '%' . $kab_kota . '%'],['province.name', 'like', '%' . $provinsi . '%']]);
		}elseif(count($path) == 3){
			$canonical = $path[0].'/'.$path[1].'/'.$path[2];
			$kab_kota = str_replace('-', ' ', $path[2]);
			$provinsi = str_replace('-', ' ', $path[1]);
			$meta_title 		= ucwords('Kode Pos Kota '.$kab_kota);
			$meta_description 	= ucwords('Berikut Kode Pos Kota '.$kab_kota.' Provinsi '.$provinsi);
			$this->data['header'] 	= ucwords('Kode Pos Kota '.$kab_kota.' - Provinsi '.$provinsi);
			$cek_alamat = Postcode::join('rgn_subdistrict', 'rgn_subdistrict.id', 'postcode.subdistrict_id')->join('rgn_district', 'rgn_district.id', 'postcode.district_id')->join('city', 'city.id', 'postcode.city_id')->join('province', 'province.id', 'postcode.province_id')->select('postcode.*', 'rgn_subdistrict.name as subdistrict_name', 'rgn_district.name as district_name', 'city.name as city_name', 'province.name as province_name')->Where([['city.name', 'like', '%' . $kab_kota . '%'],['province.name', 'like', '%' . $provinsi . '%']]);
		}elseif(count($path) == 2){
			$canonical = $path[0].'/'.$path[1];
			$provinsi = str_replace('-', ' ', $path[1]);
			$meta_title 		= ucwords('Kabupaten '.$kab_kota);
			$meta_description 	= ucwords('Berikut Kode Pos Provinsi '.$provinsi);
			$this->data['header'] 	= ucwords('Kode Pos Provinsi '.$provinsi);
			$cek_alamat = Postcode::join('rgn_subdistrict', 'rgn_subdistrict.id', 'postcode.subdistrict_id')->join('rgn_district', 'rgn_district.id', 'postcode.district_id')->join('city', 'city.id', 'postcode.city_id')->join('province', 'province.id', 'postcode.province_id')->select('postcode.*', 'rgn_subdistrict.name as subdistrict_name', 'rgn_district.name as district_name', 'city.name as city_name', 'province.name as province_name')->Where([['province.name', 'like', '%' . $provinsi . '%']]);
		}
		
		$this->data['meta']                 = ['title' => $meta_title, 'keywords' => $meta_keyword, 'description' => $meta_description];
		$this->data['canonical']  			= $canonical;
		$this->data['data'] = $cek_alamat->paginate(20);
		return $this->render_view('pages.pages.cek_alamat');
	}

	public function provinsi(request $request){
		$path = $this->data['path2'];
		$canonical 	= 'provinsi/'.$path[1];
		$provinsi = str_replace('-', ' ', $path[1]);
		$q 		= $cek_alamat = Postcode::join('rgn_subdistrict', 'rgn_subdistrict.id', 'postcode.subdistrict_id')->join('rgn_district', 'rgn_district.id', 'postcode.district_id')->join('city', 'city.id', 'postcode.city_id')->join('province', 'province.id', 'postcode.province_id')->select('postcode.*', 'rgn_subdistrict.name as subdistrict_name', 'rgn_district.name as district_name', 'city.name as city_name', 'province.name as province_name')->Where('province.name', 'like', '%' . $provinsi . '%')->firstOrFail();
 		$cek_alamat = Postcode::join('rgn_subdistrict', 'rgn_subdistrict.id', 'postcode.subdistrict_id')->join('rgn_district', 'rgn_district.id', 'postcode.district_id')->join('city', 'city.id', 'postcode.city_id')->join('province', 'province.id', 'postcode.province_id')->select('postcode.*', 'rgn_subdistrict.name as subdistrict_name', 'rgn_district.name as district_name', 'city.name as city_name', 'province.name as province_name')->Where('province.name', 'like', '%' . $provinsi . '%');
		$meta_title 		= ucwords('Kumpulan Kode Pos Provinsi '.$provinsi);
		$meta_keyword 		= 'Pos Indonesia';
		$meta_description 	= ucwords('Berikut Daftar Informasi Kode Pos Provinsi '.$provinsi.' Dengan Berbagai Data Kota Kecamatan Kelurahan Yang Tersedia');
		// dd($cek_alamat->get());
		$this->data['meta']                 = ['title' => $meta_title, 'keywords' => $meta_keyword, 'description' => $meta_description];
		$this->data['canonical']  			= $canonical;
		$this->data['header'] 				= ucwords('Kode Pos Provinsi '.$provinsi);
		$this->data['data'] = $cek_alamat->paginate(20);

		$page = $request->input('page');
		if($page != null ){
			$this->data['meta_robots'] = 'noindex';
		} 
		return $this->render_view('pages.pages.cek_alamat');
	}

	public function kodepos(request $request){
		$path = $this->data['path2'];
		$canonical 	= 'kodepos/'.$path[1];
		$search = str_replace('-', ' ', $path[1]);
		$cek_alamat = Postcode::join('rgn_subdistrict', 'rgn_subdistrict.id', 'postcode.subdistrict_id')->join('rgn_district', 'rgn_district.id', 'postcode.district_id')->join('city', 'city.id', 'postcode.city_id')->join('province', 'province.id', 'postcode.province_id')->select('postcode.*', 'rgn_subdistrict.name as subdistrict_name', 'rgn_district.name as district_name', 'city.name as city_name', 'province.name as province_name')->Where('postcode.postcode', 'like', '%' . $search . '%');
		$meta_title 		= ucwords('Daerah Dengan Kode Pos '.$search);
		$meta_keyword 		= 'Pos Indonesia';
		$meta_description 	= ucwords('Berikut Daftar Informasi Kode Pos '.$search.' Dengan Berbagai Data Kota Kecamatan Kelurahan Yang Tersedia.');
		// dd($cek_alamat->get());
		$this->data['meta']                 = ['title' => $meta_title, 'keywords' => $meta_keyword, 'description' => $meta_description];
		$this->data['canonical']  			= $canonical;
		$this->data['data'] = $cek_alamat->paginate(20);
		$page = $request->input('page');
		if($page != null ){
			$this->data['meta_robots'] = 'noindex';
		} 
		$this->data['header'] 	= ucwords('Daerah Dengan Kode Pos '.$search);
		return $this->render_view('pages.pages.cek_alamat');
	}

	function getrss(){
		$q=$_GET["q"];
		dd($q);
		//find out which feed was selected
		if($q=="Google") {
		  $xml=("http://news.google.com/news?ned=us&topic=h&output=rss");
		} elseif($q=="NBC") {
		  $xml=("http://rss.msnbc.msn.com/id/3032091/device/rss/rss.xml");
		}

		$xmlDoc = new DOMDocument();
		$xmlDoc->load($xml);

		//get elements from "<channel>"
		$channel=$xmlDoc->getElementsByTagName('channel')->item(0);
		$channel_title = $channel->getElementsByTagName('title')
		->item(0)->childNodes->item(0)->nodeValue;
		$channel_link = $channel->getElementsByTagName('link')
		->item(0)->childNodes->item(0)->nodeValue;
		$channel_desc = $channel->getElementsByTagName('description')
		->item(0)->childNodes->item(0)->nodeValue;

		//output elements from "<channel>"
		echo("<p><a href='" . $channel_link
		  . "'>" . $channel_title . "</a>");
		echo("<br>");
		echo($channel_desc . "</p>");

		//get and output "<item>" elements
		$x=$xmlDoc->getElementsByTagName('item');
		for ($i=0; $i<=2; $i++) {
		  $item_title=$x->item($i)->getElementsByTagName('title')
		  ->item(0)->childNodes->item(0)->nodeValue;
		  $item_link=$x->item($i)->getElementsByTagName('link')
		  ->item(0)->childNodes->item(0)->nodeValue;
		  $item_desc=$x->item($i)->getElementsByTagName('description')
		  ->item(0)->childNodes->item(0)->nodeValue;
		  echo ("<p><a href='" . $item_link
		  . "'>" . $item_title . "</a>");
		  echo ("<br>");
		  echo ($item_desc . "</p>");
		}
	}
}
