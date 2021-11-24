<?php namespace digipos\Http\Controllers\Admin;

use digipos\Libraries\Alert;
use digipos\Libraries\Currency;
use digipos\Libraries\Report;

use digipos\models\Mscurrency;

use Session;
use Request;
use DB;
use App;
use Validator;
use Image;
use File;
use Auth;
use Hash;
use Schema;
use Carbon\Carbon;

class KyubiController extends Controller {
	public function __construct(){
		parent::__construct();
		$this->data['head_button'] = '';
	}

	public $field;
	public $datareport;
	public $head_button 	= '';
	public $scripts			= '';
	public $title;
	public $root_link;
	public $model;
	public $primary_field 	= 'id';
	public $query_method 	= 'model';
	public $tab_data 		= [];
	public $restrict_id 	= [];
	public $restrict_delete = [];
	public $bulk_action 	= false;
	public $bulk_action_data = [];
	public $condition_disable_action = [];
	
	//sorting
	public $order_method 	= 'single';
	public $order_field 	= 'order';
	public $order_field_by 	= 'asc';

	public function hidden($data){
		$gen = view($this->view_path.'.builder.hidden',$data)->render();
		return $gen;
	}

	public function generate_token(){
		$token = csrf_token();
		return $token;
	}

	public function build_input($f){
		if (in_array($f['type'],['text','email','password','number'])){
			$content = view($this->view_path.'.builder.text',$f);
		}else if($f['type'] == 'hidden'){
			$content = view($this->view_path.'.builder.hidden',$f);
		}else if($f['type'] == 'select'){
			$content = view($this->view_path.'.builder.select',$f);
		}else if($f['type'] == 'radio'){
			$content = view($this->view_path.'.builder.radio',$f);
		}else if($f['type'] == 'textarea'){
			$content = view($this->view_path.'.builder.textarea',$f);
		}else if($f['type'] == 'file'){
			$content = view($this->view_path.'.builder.file',$f);
		}else if($f['type'] == 'checkbox'){
			if(isset($f['hasmany'])){
				$f['value'] = $this->build_array($this->model->{$f['hasmany']['method']},$f['hasmany']['primary'],$f['hasmany']['field']);
			}
			$content = view($this->view_path.'.builder.checkbox',$f);
		}
		return $content;
	}

	public function build_array($method,$primary,$field){
		$data 	= [];
		if($primary == ''){
			foreach($method as $u){
				$data[] = $u->$field;
			}
		}else{
			foreach($method as $u){
				$data[$u->$primary] = $u->$field;
			}
		}
		return $data;
	}

	public function build_validation(){
		$validate = '';
		foreach($this->field as $f){
			if (isset($f['validation'])){
				$validate = array_add($validate,$f['name'],$f['validation']);
			}
		}
		$v = Validator::make(Request::all(),$validate);
		return $v;
	}

	public function build_image($f){
		// dd($f['file_opt']['path']);
		if (!file_exists($f['file_opt']['path'])) {
		    mkdir($f['file_opt']['path'], 0777, true);
		}
		$extension 	= Request::file($f['name'])->getClientOriginalExtension();
		if(in_array($extension,['pdf','word','zip','xlsx','xls','rar','ppt','mp3','docx'])){
			// $filename 	= $f['name'].'.'.$extension;
			$filename 	= Request::file($f['name'])->getClientOriginalName();
			// $filename 	= str_replace(" ","_",$filename);
			Request::file($f['name'])->move($f['file_opt']['path'], $filename);
		}else{
			if(isset($f['crop']) && $f['crop'] == 'y'){
				$img = Request::input($f['name'].'_crop');
				$img = str_replace('data:image/png;base64,', '', $img);
				$img = str_replace(' ', '+', $img);
				$thumbnail = base64_decode($img);
				$filename 	= str_random(7).'.png';
				file_put_contents($f['file_opt']['path'].$filename, $thumbnail);
			}else{
				$filename = str_random(6) . '_' . str_replace(' ','_',Request::file($f['name'])->getClientOriginalName());   
				$image = Image::make(Request::file($f['name'])->getRealPath());
				if (isset($f['file_opt']['width']) && isset($f['file_opt']['height'])){
			        $image = $image->resize($f['file_opt']['width'],$f['file_opt']['height']);
				}
				$image = $image->save($f['file_opt']['path'].$filename);				
			}
			if(isset($f['old_image'])){
				File::delete($f['file_opt']['path'].$f['old_image']);
			}
		}
		return $filename;
	}

	public function buildupdateflag(){
		$field 	= Request::input('field');
		$id 	= Request::input('id');
		$value 	= Request::input('value');

		if(App('role')->edit == 'y'){
			$field_id = 'id';
			// if($this->model['table'] == 'msmerchant'){
			// 	$field_id = 'merchant_id';
			// }

			if($field == 'status_service'){
				$field = 'status';
			}

			$this->model->where($field_id, $id)->update([$field => $value, 'upd_by'=>auth()->guard($this->guard)->user()->id]);
			if ($value == 'y'){
				return "<td><a href='".url($this->data['path'])."/ext/updateflag?field=".$field."&id=".$id."&value=n' class='ajax-update'><i class='fa fa-check text-success'></i></a></td>";
			}else{
				return "<td><a href='".url($this->data['path'])."/ext/updateflag?field=".$field."&id=".$id."&value=y' class='ajax-update'><i class='fa fa-close text-danger'></i></a></td>";
			}
		}else{
			return 'no_access';
		}
	}

	public function buildbulkedit(){
		$action = Request::input('action');
		$data 	= Request::input('data');
		$role 	= App('role');
		if($role->edit == 'y'){
			if($action == 'delete' && $role->delete == 'y'){
				$cnt 		= 0;
				$cnt_del 	= 0;

				foreach($data as $d){
					$query = $this->model->where('id',$d)->first();
					$ad 	= true;

					//Check Relation for delete
					if(isset($this->check_relation)){
						foreach($this->check_relation as $d){
							$q = count($query->$d);
							if ($q > 0){
								$ad = false;
							}
						}
					}
					if($ad == true){
						//Delete Relation with raw
						if(isset($this->delete_relation)){
							foreach($this->delete_relation as $d){
								$query->$d()->delete();
							}
						}
						$cnt++;
						$query->delete();
					}else{
						$cnt_del++;
					}
				}

				if($cnt_del > 0){
					Alert::fail($cnt_del." data deleted, ".$cnt." data can't deleted");
				}else{ 
					Alert::success("Success delete all selected data");
				}
			}else if($action == 'edit'){
				$name 	= Request::input('name');
				$value 	= Request::input('value');
				if($name == 'status_service'){
					$name = 'status';
				}
				$this->model->whereIn('id',$data)->update([$name => $value]);
				Alert::success(count($data)." data updated");
			}
		}else{
			Alert::fail("You don't have access");
		}
		return response()->json(['status' => 'continue']);
	}

	public function build_export($par = ''){
		$type 		= Request::input('type');
		$schema 	= Schema::getColumnListing($this->model->getTable());
		if($type == 'excel'){
			if($par != ''){
				$this->model = $this->model->whereIn('id', $par);
			}
			$data 	= ['header'	=> $schema,'data' => $this->model->get()];
			// dd($data);
			Report::setdata($data);
			Report::settitle($this->title);
			Report::setview('admin.builder.excel');
			Report::settype($type);
			Report::setformat('xlsx');
			Report::setcreator(auth()->guard($this->guard)->user()->email);
			Report::generate();
		}
	}

	public function build_export_cus($par = ''){
		$type 		= Request::input('type');
		$schema 	= Schema::getColumnListing($this->model->getTable());
		$diff = ["customer_id", "created_at", "updated_at"];
		$schema = array_diff( $schema, $diff );

		if($type == 'excel'){
			if($par != ''){
				$this->model = $this->model->whereIn('id', $par);
			}
			$data 	= ['header'	=> $schema,'data' => $this->model->join('order_status', 'order_status.id', 'orderhd.order_status')->where('type_order', 'not like', '%post%')->select('orderhd.id', 'orderhd.transaction_id', 'orderhd.name', DB::raw('FORMAT(bel_orderhd.total_price, 0) as total_price'), 'orderhd.type_order', 'order_status.desc as order_status', DB::raw('DATE_FORMAT(bel_orderhd.order_date, "%d - %M - %Y") as order_date'))->get()];
			Report::setdata($data);
			Report::settitle($this->title);
			Report::setview('admin.builder.excel');
			Report::settype($type);
			Report::setformat('xlsx');
			Report::setcreator(auth()->guard($this->guard)->user()->email);
			Report::generate();
		}
	}

	public function build_export2($par = ''){
		  $type   = Request::input('type');
		  $schema  = Schema::getColumnListing($this->model->getTable());
		  $diff = ["password", "created_at", "updated_at", "reset_password_token", "social_account_id", "social_account_type", "latitude", "longitude", "remember_token", "upd_by", "language_id"];
		  $schema = array_diff( $schema, $diff );
		  if($type == 'excel'){
		   if($par != ''){
		    $this->model = $this->model->whereIn('id', $par);
		   }

		   $t = [];
		   foreach($this->model->get() as $tm){
		    foreach($diff as $d){
		     unset($tm->$d);
		    }

		    $t[] = $tm;
		   }

		   $data  = ['header' => $schema,'data' => $t];
		   // dd($data);
		   Report::setdata($data);
		   Report::settitle($this->title);
		   Report::setview('admin.builder.excel');
		   Report::settype($type);
		   Report::setformat('xlsx');
		   Report::setcreator(auth()->guard($this->guard)->user()->email);
		   Report::generate();
		  }
	 }

	public function build_alias($data){
		$data = preg_replace('/[^a-zA-Z0-9]/', '-', $data);
		return $data;
	}

	public function build_index(){
		//Set Paging Data
		$pag_data = [];
		$id = 'id';
		$current_date = date("Y-m-d");
		// dd($current_date);
		// if($this->model['table'] == 'msmerchant'){
		// 	$id = 'merchant_id';
		// }

		$query = $this->model->whereNotIn($id,$this->restrict_id);
		// dd(Request::all());
		foreach(Request::all() as $r => $q){
			$pag_data = array_add($pag_data,$r,$q);
		}

		$orderby = Request::input('orderby') ? Request::input('orderby') : $id;
		if ($this->data['role']->create == 'y'){
			$this->head_button .= "<a href='".url($this->data['path'].'/create')."' class='font-dash font-16 margin-left-20'><i class='fa fa-plus fa-lg'></i></a>";
        }

        if(count($query->get()) > 0){
	        /*if ($this->data['role']->sorting == 'y'){
				$this->head_button .= view($this->view_path.'.builder.link',['url' => $this->data['path'].'/ext/sorting','class' => 'purple-medium','label' => '<i class="fa fa-align-justify"></i> '.trans('general.sorting')])." ";
	        }*/
	        if ($this->data['role']->export == 'y'){
	        	$this->head_button .= '<a href="'.url($this->data['path']).'/ext/export?type=excel" class="font-dash font-16 margin-left-20">
						                        <i class="fa fa-file-excel-o fa-lg"></i></a>';
	        }
	        if (isset($this->import) && $this->import == true){	
	        	$this->head_button .= '<a class="font-dash font-16 margin-left-20" href="'.url($this->data['path']).'/ext/import"><i class="fa fa-file-excel-o fa-lg"></i></a>';
	        }

        	$this->head_button .= '<a class="font-dash font-16 search-head margin-left-20"><i class="fa fa-search fa-lg"></i></a>';

			$content = '<div class="table-responsive"><table class="table table-bordered">';
			$content .= '<form method="get" id="builder_form" action="'.url($this->data['path']).'" data-ask="n"></form>';
			$content .= view($this->view_path.'.includes.errors');
			//SearchBox
			if(Request::has('q')){
				$content .= '<tr class="search-head-content">';
			}else{
				$content .= '<tr class="search-head-content" style="display:none;">';
			}
			$content .= '<th></th>';
			if($this->bulk_action == true && $this->data['role']->edit == 'y'){
		    	$content .= '<th></th>';
		    }
		    
		    // flag desc in Pagecontroller
		    $flag_desc = 0;
		    // var_dump($this->field);
		    foreach($this->field as $key => $f){
	    		if (isset($f['search'])){
		        	if ($f['search'] == 'text'){
		        			$content .= '<th><div class="form-group form-md-line-input">';
				          	$content .= '<input type="text" placeholder="'.$f['label'].'" id="form_floating_'.$key.'" class="form-control search-data '.(isset($f['class']) ? $f['class'] : "").'" name="'.$f['name'].'" value="'.(Request::has($f['name']) ? Request::input($f['name']) : "").'" '.(isset($f['attribute']) ? $f['attribute'] : "").'>';
				          	$content .= '</div></th>';
		        	}else if ($f['search'] == 'select'){
		        		$content .= '<th><div class="form-group form-md-line-input">';
		        			$content .= '<select class="form-control search-data '.(isset($f['class']) ? $f['class'] : '').'" name="'.$f["name"].'"><option value="" '.(Request::input($f['name']) == '' ? 'selected' : '').'>--'.trans("general.select_one").'--</option>';
		        		
		        		if (isset($f['search_data'])){
		        			foreach($f['search_data'] as $fs => $fq){
		        				$content .= "<option value='".$fs."' ".(Request::input($f['name']) == $fs ? 'selected' : '').">".$fq."</option>";
		        			}
		        		}
		        		$content .= '</select></div></th>';
		        	}else if($f['search'] == 'date'){
		        		$content .= '<th><div class="form-group form-md-line-input">';
				          	$content .= '<input type="date" placeholder="'.$f['label'].'" id="form_floating_'.$key.'" class="form-control search-data '.(isset($f['class']) ? $f['class'] : "").'" name="'.$f['name'].'" value="'.(Request::has($f['name']) ? Request::input($f['name']) : "").'" '.(isset($f['attribute']) ? $f['attribute'] : "").'>';
				          	$content .= '</div></th>';
		        	}
		        	// dd($content);
		        	if(isset($f['query_type']) && Request::has($f['name'])){
		        		if(isset($f['query_type']['range']) && $f['query_type']['range'] == 'from'){
		        			$query = $query->where($f['name'],'>=',Request::input($f['name']));
		        		}
		        		else if(isset($f['query_type']['date']) && $f['query_type']['date'] == 'equal'){
		        			$query = $query->whereDate($f['name'],'=',$this->displayToSql(Request::input($f['name'])));
		        		}
		        		else{
		        			$query = $query->where($f['name'],'<=',Request::input($f['name']));
		        		}
		        	}else{
		        		
	        			// dd(Request::all());
		        		if((int)Request::input($f['name'])){
		        			if($f['search'] == 'date'){
			        			$query = $query->whereRaw('DATE_FORMAT(bel_orderhd.order_date,"%Y-%m-%d") = "'.Request::input($f['name']).'"');
		        			} else{
			        			$query = $query->where($f['name'],'=',Request::input($f['name']));
		        			}
			        	}else{
			        		// dd($f);
			        		if($f['name'] == 'province_name' || $f['name'] == 'city_name' || $f['name'] == 'church_name' || $f['name'] == 'booking_time' || $f['name'] == 'image' || $f['name'] == 'file'){
			        			
			        		}else{
				        		$query = $query->where($f['name'],'like','%'.Request::input($f['name']).'%');
				        		// dd($f['name']);
			        		}
			        	}
		        	}

		        }else{
		        	$content .= "<th></th>";
		        }
		    }

		    $content .= '<th colspan="3">'.view($this->view_path.'.builder.button',['type' => 'submit','label' => '<i class="fa fa-search"></i> '.trans('general.filter'),'class' => 'submit-search']).view($this->view_path.'.builder.link',['url' => $this->data['path'],'label' =>'<i class="fa fa-refresh"></i> Reset','class' => 'red-mint']).'</th></tr>';
		   
		    //Header for datatable
		    $content .= '<tr>';
		    if($this->bulk_action == true && $this->data['role']->edit == 'y'){
		    	$content .= '<th></th>';
		    }
		    $content .= '<th>No</th>';
		    foreach($this->field as $f){
		        if (isset($f['sorting']) && $f['sorting'] == 'y'){
		          	$content .= '<th><a href="#" class="sorting-data" data-orderby="'.$f['name'].'">
			            	'.$f['label'];
			        if(Request::has('orderby') && request::input('orderby') == $f['name']){
			        	if(request::input('orderdata') == 'asc'){
			        		$content .= ' <i class="fa fa-arrow-down"></i>';
			        	}else{
			        		$content .= ' <i class="fa fa-arrow-up"></i>';
			        	}
			        }else{
			        	$content .= ' <i class="fa fa-arrows-v"></i>';
			        }
			        $content .= '</a></th>';
		        }else{
		          $content .= "<th>".$f['label']."</th>";
		        }
		    }
		    $content .= '<th colspan="3"></th></tr>';
		    //Data
		   	if(isset($this->condition_disable_action['edit'])){
		    	$query_real = $query->orderBy($orderby,Request::input('orderdata'))->paginate(10);
			}
		    $query = $query->orderBy($orderby,Request::input('orderdata'))->paginate(10);
		    
		    foreach($query as $idx => $q){
		    	if($this->title == 'Order'){
		    		if($q->order_status_id == 7 && ($q->return_date < $current_date)){
		    			$content .= '<tr class="background-color-red">';
		    		}else{
		    			$content .= '<tr>';
		    		}
		    	}else{
		    		$content .= '<tr>';
		    	}
		    	if($this->bulk_action == true && $this->data['role']->edit == 'y'){
		    		$content .= '<td class="vcenter"><center>'.$this->build_input(['type' => 'checkbox','name' => 'bulkaction_id','data' => [$q->id => ''],'class' => 'bulk_checkbox']).'</center></td>';
		    	}
		    	$content .= "<td class='vcenter'>".$this->data['no']++."</td>";
		    	foreach($this->field as $f){
		    		if(isset($f['belongtoraw'])){
		    			$q->{$f['name']} = $q->{$f['belongtoraw']['field']};
		    		}
		    		if(isset($f['before_show'])){
	    				if(array_key_exists('date_format', $f['before_show'])){
		    				$date 	= $q->{$f['name']};
		    				$format = $f['before_show']['date_format']['format'];
							$content .= "<td class='vcenter'>".date($format, strtotime($date))."</td>";
			    		}
			    		if(array_key_exists('format_currency',$f['before_show'])){
		    				$currency = Mscurrency::find($q->fkcurrencyid);
		    				$q->{$f['name']} = Currency::convert($q->{$f['name']},$currency);
		    				$content .= "<td class='vcenter'>".$q->{$f['name']}."</td>";
		    			}

		    			if(array_key_exists('number_format',$f['before_show'])){
		    				$q->{$f['name']} = number_format($q->{$f['name']},$f['before_show']['number_format']['decimal'],',','.');
		    				$content .= "<td class='vcenter'>".$q->{$f['name']}."</td>";
		    			}	
		    		}else if (isset($f['type']) && $f['type'] == 'check'){
			    		if ($q->{$f['name']} == 'y' || $q->{$f['name']} == '1'){
			    			$content .= "<td class='vcenter'><a href='".url($this->data['path'])."/ext/updateflag?field=".$f['name']."&id=".$q->id."&value=n' class='ajax-update'><i class='fa fa-check text-success'></i></a></td>";
			    		}else{
			    			$content .= "<td class='vcenter'><a href='".url($this->data['path'])."/ext/updateflag?field=".$f['name']."&id=".$q->id."&value=y' class='ajax-update'><i class='fa fa-close text-danger'></i></a></td>";
			    		}
					}else if (isset($f['type']) && $f['type'] == 'image'){
						if(isset($f['belongto'])){
							$q->{$f['name']} = $q->{$f['belongto']['method']}()->first();
							if($q->{$f['name']}){
								$q->{$f['name']} = $q->{$f['name']}->{$f['belongto']['field']};
							}
						}

						if(isset($f['file_opt']['custom_path'])){
							$f['file_opt']['path'] 	= $f['file_opt']['path'].$q->{$f['file_opt']['custom_path']}.'/';
						}

						if(isset($f['file_opt']['custom_path_id']) && $f['file_opt']['custom_path_id'] == 'y'){
							$f['file_opt']['path'] 	= $f['file_opt']['path'].$q->id.'/';
						}

						if($q->{$f['name']}){
							if($this->root_link == 'manage-mitra'){
								$q->{$f['name']} = asset($f['file_opt']['path'].$q->id.'/'.$q->{$f['name']});
							}else{
								$q->{$f['name']} = asset($f['file_opt']['path'].$q->{$f['name']});
							}
						}else{
							$q->{$f['name']} = asset('components/both/images/web/none.png');
						}
						$content .= "<td class='vcenter'><img src='".$q->{$f['name']}."' width='50px'></td>";
					}else{
						$wrapper_class = "";
						if(isset($f['belongto'])){
							if(isset($f['belongto']['method2'])){
								if($q->{$f['belongto']['method']}()->first()){
									if($q->{$f['belongto']['method']}->{$f['belongto']['method2']}()->first()){
										$q->{$f['name']} = $q->{$f['belongto']['method']}->{$f['belongto']['method2']}()->first()->{$f['belongto']['field']};
									}
									else{
										$q->{$f['name']} = '';
									}
								}
								else{
									$q->{$f['name']} = '';
								}
							}
							else if($q->{$f['belongto']['method']}()->first()){
								$q->{$f['name']} = $q->{$f['belongto']['method']}()->first()->{$f['belongto']['field']};
							}
							else{
								$q->{$f['name']} = '';
							}
						}
						if(isset($f['value_key'])){
							$q->{$f['name']} = $f['value_key'][$q->{$f['name']}];
						}
						if(isset($f['after_show'])){
			    			if(array_key_exists('label',$f['after_show'])){
			    				if(array_key_exists('onclick',$f['after_show']) && in_array($q->{$f['name']}, $f['after_show']['onclick']['include'])){
			    					$wrapper_class = "wrapper-".$f['name']."-".$q->id;
			    					$q->{$f['name']} = '<span class="label label-'.$f['after_show']['label'][$q->{$f['name']}].'" style="cursor:pointer;" onclick="'.$f['after_show']['onclick']['function'].'('.$q->{$f['after_show']['onclick']['data']}.')">'.$q->{$f['name']}.'</span>';
			    				}
			    				else{
			    					$q->{$f['name']} = '<span class="label label-'.$f['after_show']['label'][$q->{$f['name']}].'">'.$q->{$f['name']}.'</span>';
			    				}
			    			}
			    		}

						if(isset($f['type']) && $f['type'] == 'file'){
							$content .= "<td class='vcenter ".$wrapper_class."'><a href='".$f['file_opt']['path'].$q->{$f['name']}."' target='_blank'>".$q->{$f['name']}."</a></td>";
						}else{
							$content .= "<td class='vcenter ".$wrapper_class."'>".$q->{$f['name']}."</td>";
						}
					}
		    	}

		    	$content .= "<td class='vcenter'>";
		    	if ($this->data['role']->view == 'y'){
		    		if(isset($this->url_modification)){
		    			$content .= view($this->view_path.'.builder.link',['class' => 'green-jungle', 'url' => $this->data['path'].'/'.$this->url_modification,'label' => trans('general.view')]);
		    		}else{
		    			$content .= view($this->view_path.'.builder.link',['class' => 'green-jungle', 'url' => $this->data['path'].'/'.$q->id,'label' => trans('general.view')]);
		    		}
		    	}
		    	if ($this->data['role']->edit == 'y'){
		    		if(isset($this->condition_disable_action['edit'])){
		    			$condition_disable_status 	= 0;
		    			$total_promoted 			= 0;
		    			$condition_loop  			= $this->condition_disable_action['edit']['condition'];
		    			foreach($this->condition_disable_action['edit']['data'] as $ca => $qa){
		    				// var_dump('index: '.$ca);
		    				// var_dump($query_real[$idx]);
		    				// var_dump('name: '.$query_real[$idx]->promo_name);
		    				// var_dump('promote_date: '.$query_real[$idx]->promote_date);
		    				$query_row 	= $query_real[$idx]->{$ca};
		    				$promote_date 	= $query_real[$idx]->promote_date;
		    				if(isset($qa['type'])){
			    				if(isset($qa['inverse']) && $qa['inverse'] == '!='){
				    					if($qa['type'] != 'date'){

										}else{
						    					if($qa['type'] == 'date'){
											}
										}
		    						$qa['value']	= Carbon::parse($qa['value'])->format('Y-m-d');
		    						$query_row		= Carbon::parse($query_row)->format('Y-m-d');
		    					}
		    				}
		    				if($condition_loop == 'and'){
		    					if(isset($qa['inverse'])){
		    						if($qa['inverse'] == '!='){
					    				if($qa['value'] != $query_row){

										}else{
					    					if($qa['value'] == $query_row){
												$condition_disable_status += 1;
												// var_dump('$condition_disable_status and: '.$condition_disable_status);
										}
									}
				    				}else{
				    					break;
				    				}
		    					}else{
		    						break;
		    					}
			    			}else{
			    				// if(($qa['value'] != $query_row)){
				    			// 	$condition_disable_status += 1;
				    			// 	var_dump('condition_disable_status 2: '.$condition_disable_status);
			    				// }else{
			    				// 	break;
			    				// }

			    				if($ca == 'total_promote'){
			    					if($qa['value'] != null){
			    						$limit_promoted = $qa['value'];
			    					}
			    					$total_promoted = $query_row;
			    				}else if($ca == 'created_at'){
				    				if($qa['value'] != null && $promote_date != null){
				    					// var_dump('date now: '.$qa['value']);
				    					// var_dump('$promote_date: '.$promote_date);
					    				if(strtotime($qa['value']) > strtotime($promote_date)){
						    				$condition_disable_status += 1;
						    				// var_dump('$condition_disable_status 1: '.$condition_disable_status);
					    				}else if(strtotime($qa['value']) == strtotime($promote_date)){
					    					// var_dump('$total_promoted: '.$total_promoted);
				    						// var_dump('$limit_promoted: '.$limit_promoted);
					    					if($total_promoted < $limit_promoted){
					    						$condition_disable_status += 1;
					    						// var_dump('$condition_disable_status 2: '.$condition_disable_status);
					    					}
					    				}
					    			}else{
					    				$condition_disable_status += 1;
						    			// var_dump('$condition_disable_status 3: '.$condition_disable_status);
					    			}
					    		}
			    			}
		    			}

		    			if($condition_loop == 'and'){
			    			if($condition_disable_status == count($this->condition_disable_action['edit']['data'])){
				    			$content .= view($this->view_path.'.builder.link',['url' => $this->data['path'].'/'.$q->id.'/edit','label' => trans('general.edit')]);
							}
						}else{
							// var_dump('condition_disable_status total: '.$condition_disable_status);
				   //  		var_dump('count: '.count($this->condition_disable_action['edit']['data']));
							if($condition_disable_status > 0 && $condition_disable_status != count($this->condition_disable_action['edit']['data'])){
				    			$content .= view($this->view_path.'.builder.link',['url' => $this->data['path'].'/'.$q->id.'/edit','label' => trans('general.edit')]);
							}
						}
		    		}else{
		    			if($this->title == 'Notification' || $this->title == 'Notifikasi'){

		    			}else{
		    				$content .= view($this->view_path.'.builder.link',['url' => $this->data['path'].'/'.$q->id.'/edit','label' => trans('general.edit')]);
		    			}
		    		}
		    	}

		    	//export pdf
		    	if ($this->title == 'Order' && (isset($this->export_pdf) && $this->export_pdf == true)){
			    	$content .= '<a href="'.url($this->data['path']).'/ext/export_pdf?id='.$q->id.'" class="font-16 margin-left-20 btn blue">
						                        <i class="fa fa-file-pdf-o fa-lg"></i></a>';
		    	}

		    	//Delete
		    	if ($this->data['role']->delete == 'y' && !in_array($q->id,$this->restrict_delete)){
		    		$content .= "<form method='post' action='".url($this->data['path'].'/destroy/')."'>";
			    	$content .= $this->hidden(['name' => 'id','value' => $q->id]);
			    	$content .= $this->hidden(['name' => '_method','value' => 'delete']);
			    	$content .= $this->hidden(['name' => '_token','value' => $this->generate_token()]);
			    	$content .= view($this->view_path.'.builder.button',['type' => 'submit','class' => 'red-mint','label' => trans('general.delete')]);
		    	}

		    	$content .= "</form>";
		    	$content .= '</td></tr>';
		    }

		    $content .= '</table></div>';
		    $query->setPath($this->root_link);
		    $content .= '<center>'.$query->appends($pag_data)->render().'</center>';

		    //Dropdown Bulk
		    if($this->bulk_action == true && $this->data['role']->edit == 'y' && count($query) > 0){
		    	$content .= '
					    	<div class="btn-group">
				                <a class="btn purple" href="javascript:;" data-toggle="dropdown">
				                    <i class="fa fa-bars"></i> '.trans('general.bulk-actions').'
				                    <i class="fa fa-angle-up"></i>
				                </a>
				                <ul class="dropdown-menu bottom-up bulk-actions">
				                    <li>
				                        <a class="checkall" data-target="bulk_checkbox">
				                        <i class="fa fa-check-square-o true"></i> '.trans('general.select-all').' </a>
				                    </li>
				                    <li>
				                        <a class="uncheckall" data-target="bulk_checkbox">
				                        <i class="fa fa-square-o false"></i> '.trans('general.deselect-all').' </a>
				                    </li>';
				
				foreach($this->bulk_action_data as $bad){
					$content .= '<li class="divider"> </li>
					                    <li>
					                        <a class="ajax-bulk-update" data-href="'.url($this->data['path'].'/ext/bulkupdate/').'" class="ajax-bulk-update" data-action="edit" data-alert="'.trans('general.are-you-sure').'" data-name="'.$this->field[$bad]['name'].'" data-value="y">
					                        	<i class="fa fa-power-off true"></i> '.trans('general.enable-all-selected').' '.trans('general.'.strtolower($this->field[$bad]['label'])).' 
					                      	</a>
					                    </li>
					                    <li>
					                        <a class="ajax-bulk-update" data-href="'.url($this->data['path'].'/ext/bulkupdate/').'" class="ajax-bulk-update" data-action="edit" data-alert="'.trans('general.are-you-sure').'" data-name="'.$this->field[$bad]['name'].'" data-value="n">
					                        	<i class="fa fa-power-off false"></i> '.trans('general.disable-all-selected').' '.trans('general.'.strtolower($this->field[$bad]['label'])).'
					                        </a>
					                    </li>
					            ';
				}

				if($this->data['role']->delete == 'y'){
					$content 	.=  '<li class="divider"> </li>
						                    <li>
						                        <a data-href="'.url($this->data['path'].'/ext/bulkupdate/').'" class="ajax-bulk-update" data-action="delete" data-alert="'.trans('general.are-you-sure').'">
						                        <i class="fa fa-trash false"></i> '.trans('general.delete-selected').' </a>
						                    </li>
						                </ul>
						            </div>';
		    	}
		    }
		     // <li>
				   //                      <a href="javascript:;">
				   //                      <i class="fa fa-edit true"></i> '.trans('general.edit-manual-selected').' </a>
				   //                  </li>

		    //Build view
		}
		else{
			$content = "<div class='row'>
				<div class='col-md-6'>
					<div class='alert alert-danger'>
						Data not available
					</div>
				</div>
			</div>";
		}

		$this->data['title'] = 'List '.$this->title;
	    $this->data['head_button'] = $this->head_button;
	    $this->data['content'] = $content;
	}

	// build html
	public function build_create(){
		$this->head_button .= view($this->view_path.'.builder.link',['url' => $this->data['path'],'label' => '<i class="fa fa-arrow-left"></i> '.trans('general.back'),'class' => 'red-mint']);
		$content = '<form role="form" method="post" action="'.url($this->data['path']).'" enctype="multipart/form-data">';
		$content .= view($this->view_path.'.includes.errors');
		if(count($this->tab_data) == 0){
			foreach($this->field as $f){
				if (!isset($f['value'])){
					$f= array_add($f,'value',old($f['name']) ? old($f['name']) : '');
				}
				$content .= $this->build_input($f);
			}
		}else{
			$tab_data = $this->tab_data;
			$content .= '<div class="tabbable-line"><ul class="nav nav-tabs">';
			$tab_loop = 0;
			foreach($tab_data as $td => $tdq){
				if($tab_loop++ == 0){
					$content .= '<li class="active">';
				}else{
					$content .= '<li>';
				}
				$content .= '<a href="#'.$td.'" data-toggle="tab" aria-expanded="true">'.$tdq.'</a>';
				$content .= '</li>';

				$content_tab[$td] = '';
			}
			$content_tab['all'] 	= '';
			$content .= '</ul><div class="tab-content">';

			foreach($this->field as $f){
				if (!isset($f['value'])){
					$f= array_add($f,'value',old($f['name']) ? old($f['name']) : '');
				}
				if(isset($f['tab'])){
					$content_tab[$f['tab']] .= $this->build_input($f);
				}else{
					$content_tab['all'] 	.= $this->build_input($f);
				}
			}

			$tab_loop = 0;
			foreach($tab_data as $td => $tdq){
				if($tab_loop++ == 0){
					$content .= '<div class="tab-pane active" id="'.$td.'">';
				}else{
					$content .= '<div class="tab-pane" id="'.$td.'">';
				}
				$content .= $content_tab[$td];
				$content .= '</div>';
			}
			$content .= '</div></div>';
			$content .= $content_tab['all'];
		}
		$content .= '<div class="clearfix"></div>';
		$content .= '<div class="box-footer">'.view($this->view_path.'.builder.button',['type' => 'submit','label' => '<i class="fa fa-floppy-o"></i> '.trans('general.submit')]).'</div></form>';
		$this->data['title'] = 'Add New '.$this->title;

		$this->data['head_button'] = $this->head_button;
		$this->data['content'] = $content;
	}

	public function build_store(){
		$validation = $this->build_validation();
		if (!$validation->fails()){
			foreach($this->field as $f){
				if (isset($f['not_same']) && $f['not_same'] == 'y'){
					$a = $this->model->where($f['name'],Request::input($f['name']))->first();
					if($a){
						Alert::fail($f['label'].' already exist');
						return "exit";
					}
				}

				if ($f['type'] == 'file'){
					if(Request::hasFile($f['name'])){
						$image = $this->build_image($f);
						$this->model->{$f['name']} = $image;
					}else{
						if(Request::input('remove-single-image-'.$f['name']) == 'y'){
							File::delete($f['file_opt']['path'].$this->model->{$f['name']});
							$this->model->{$f['name']} = '';
						}
					}
				}else if($f['type'] == 'checkbox'){
					if(count(Request::input($f['name'])) > 0){
						foreach(Request::input($f['name']) as $d){
							$temp[$f['name']][]	= new $f['hasmany']['table']([
								$f['name'] 	=> $d
							]);
						}
					}
				}else{
					$this->model->{$f['name']} = Request::input($f['name']);
					//Format Date
					if(isset($f['class'])){
						if (in_array('datepicker',explode(' ', $f['class']))) {
							$this->model->{$f['name']} = date_format(date_create(Request::input($f['name'])),'Y-m-d');
						}
						if (in_array('timepicker',explode(' ',$f['class']))) {
							// $this->model->{$f['name']} = date_format(date_create());
						}
					}

					//If Hash for encrypt or password
					if (isset($f['hash']) && $f['hash'] == 'y'){
						$this->model->{$f['name']} = Hash::make(Request::input($f['name']));
					}

					//For Make Alias
					if(isset($f['alias'])){
						$this->model->{$f['alias']} = $this->build_alias($this->model->{$f['name']});
					}
				}
			}

			$this->model->upd_by = auth()->guard($this->guard)->user()->id;
			$this->model->created_at 	= date('Y-m-d H:i:s');
			$this->model->save();

			//Checkbox in another table
			foreach($this->field as $f){
				if($f['type'] == 'checkbox'){
					if(count(Request::input($f['name'])) > 0){
						$this->model->{$f['hasmany']['method']}()->saveMany($temp[$f['name']]);
					}
				}
			}
			Alert::success('Successfully add new '.$this->title);
			return "next";
		}
	}

	public function build_edit(){
		$this->model = $this->model;

		if(in_array($this->model->id,$this->restrict_id)){
			Alert::fail("You don't have access");
			return "exit";
		}
		if(isset($this->condition_disable_action['edit'])){
			$condition_disable_status 	= 0;
			$condition_loop  			= $this->condition_disable_action['edit']['condition'];	
			foreach($this->condition_disable_action['edit']['data'] as $ca => $qa){
				$query_row 		= $this->model->{$ca};
				$promote_date 	= $this->model->promote_date;
				if(isset($qa['type'])){
    					if(isset($qa['inverse']) && $qa['inverse'] == '!='){
	    					if($qa['type'] != 'date'){
						}else{
	    					if($qa['type'] == 'date'){
						}
					}
						$qa['value']	= Carbon::parse($qa['value'])->format('Y-m-d');
						$query_row		= Carbon::parse($query_row)->format('Y-m-d');
					}
				}
				if($condition_loop == 'and'){
					if(isset($qa['inverse'])){
						if($qa['inverse'] == '!='){
		    					if($qa['value'] != $query_row){

							}else{
		    					if($qa['value'] == $query_row){
									$condition_disable_status += 1;
							}
						}
	    				}else{
	    					break;
	    				}
					}else{
						break;
					}
    			}else{
    				// if(($qa['value'] != $query_row)){
	    			// 	$condition_disable_status += 1;
    				// }else{
    				// 	break;
    				// }

    				if($ca == 'total_promote'){
    					if($qa['value'] != null){
    						$limit_promoted = $qa['value'];
    					}
    					$total_promoted = $query_row;
    				}else if($ca == 'created_at'){
	    				if($qa['value'] != null && $promote_date != null){
	    					// var_dump('date now: '.$qa['value']);
	    					// var_dump('$promote_date: '.$promote_date);
		    				if(strtotime($qa['value']) > strtotime($promote_date)){
			    				$condition_disable_status += 1;
			    				// var_dump('$condition_disable_status 1: '.$condition_disable_status);
		    				}else if(strtotime($qa['value']) == strtotime($promote_date)){
		    					// var_dump('$total_promoted: '.$total_promoted);
	    						// var_dump('$limit_promoted: '.$limit_promoted);
		    					if($total_promoted < $limit_promoted){
		    						$condition_disable_status += 1;
		    						// var_dump('$condition_disable_status 2: '.$condition_disable_status);
		    					}
		    				}
		    			}else{
		    				$condition_disable_status += 1;
			    			// var_dump('$condition_disable_status 3: '.$condition_disable_status);
		    			}
		    		}
    			}
			}
			if($condition_loop == 'and'){
    			if($condition_disable_status == count($this->condition_disable_action['edit']['data'])){
	    			Alert::fail("You don't have access");
					return "exit";
				}
			}else{
				if($condition_disable_status == 0 && $condition_disable_status != count($this->condition_disable_action['edit']['data'])){
	    			Alert::fail("You don't have access");
					return "exit";
				}else if($condition_disable_status > 0 && $condition_disable_status == count($this->condition_disable_action['edit']['data'])){
					Alert::fail("You don't have access");
					return "exit";
				}
			}
		}
		$this->head_button .= view($this->view_path.'.builder.link',['url' => $this->data['path'],'label' => '<i class="fa fa-arrow-left"></i> '.trans('general.back'),'class' => 'red-mint']);
		$content = '<form role="form" method="post" action="'.url($this->data['path']).'/'.$this->model->id.'" enctype="multipart/form-data">';
		$content .= $this->hidden(['name' => '_method','value' => 'put']);
		$content .= '<div class="form-body">';
		$content .= view($this->view_path.'.includes.errors');
		if(count($this->tab_data) == 0){
			foreach($this->field as $f){
				if (!isset($f['value'])){
					$f= array_add($f,'value',old($f['name']) ? old($f['name']) : $this->model->{$f['name']});
				}
				if(isset($f['format'])){
					if($f['format']['datetime']){
						$f['value'] = date_format(date_create($f['value']),$f['format']['datetime']);
					}
				}
				$content .= $this->build_input($f);
			}
		}else{
			$tab_data = $this->tab_data;
			$content .= '<div class="tabbable-line"><ul class="nav nav-tabs">';
			$tab_loop = 0;
			foreach($tab_data as $td => $tdq){
				if($tab_loop++ == 0){
					$content .= '<li class="active">';
				}else{
					$content .= '<li>';
				}
				$content .= '<a href="#'.$td.'" data-toggle="tab" aria-expanded="true">'.$tdq.'</a>';
				$content .= '</li>';

				$content_tab[$td] = '';
			}
			$content_tab['all'] 	= '';
			$content .= '</ul><div class="tab-content">';

			foreach($this->field as $f){
				if (!isset($f['value'])){
					$f= array_add($f,'value',old($f['name']) ? old($f['name']) : $this->model->{$f['name']});
				}
				if(isset($f['format'])){
					if($f['format']['datetime']){
						$f['value'] = date_format(date_create($f['value']),$f['format']['datetime']);
					}
				}
				if(isset($f['tab'])){
					$content_tab[$f['tab']] .= $this->build_input($f);
				}else{
					$content_tab['all'] 	.= $this->build_input($f);
				}
			}

			$tab_loop = 0;
			foreach($tab_data as $td => $tdq){
				if($tab_loop++ == 0){
					$content .= '<div class="tab-pane active" id="'.$td.'">';
				}else{
					$content .= '<div class="tab-pane" id="'.$td.'">';
				}
				$content .= $content_tab[$td];
				$content .= '</div>';
			}
			$content .= '</div></div>';
			$content .= $content_tab['all'];
		}
		$content .= '<div class="clearfix"></div>';
		$content .= '<div class="box-footer">'.view($this->view_path.'.builder.button',['type' => 'submit','label' => '<i class="fa fa-floppy-o"></i> '.trans('general.submit')]).'</div></div></form>';
		
		$pk_field = $this->primary_field;
		$this->data['title'] 		= 'Edit '.$this->title.' '.$this->model->$pk_field;
		$this->data['scripts'] 		= $this->scripts;
		$this->data['head_button'] 	= $this->head_button;
		$this->data['content'] 		= $content;
		return "next";
	}

	public function build_update(){
		$validation = $this->build_validation();
		$this->model = $this->model->first();
		if (!$validation->fails()){
			foreach($this->field as $f){
				if(isset($f['not_save']) && $f['not_save'] == 'y'){
					continue;
				}
				if (isset($f['not_same']) && $f['not_same'] == 'y'){
					if ($this->model->{$f['name']} != Request::input($f['name'])){
						$a = $this->model->where($f['name'],Request::input($f['name']))->first();
						if($a){
							Alert::fail($f['label'].' already exist');
							return "exit";
						}
					}
				}

				if ($f['type'] == 'file'){
					if (Request::hasFile($f['name'])){
						File::delete($f['file_opt']['path'].$this->model->{$f['name']});
						$image = $this->build_image($f);
						$this->model->{$f['name']} = $image;
					}else{
						if(Request::input('remove-single-image-'.$f['name']) == 'y'){
							File::delete($f['file_opt']['path'].$this->model->{$f['name']});
							$this->model->{$f['name']} = '';
						}
					}
				}else if($f['type'] == 'checkbox'){
					if(count(Request::input($f['name'])) > 0){
						foreach(Request::input($f['name']) as $d){
							$temp[]	= new $f['hasmany']['table']([
								$f['name'] 	=> $d
							]);
						}
					}
					$this->model->{$f['hasmany']['method']}()->delete();
					if(isset($temp)){
						$this->model->{$f['hasmany']['method']}()->saveMany($temp);
					}
				}else{
					if($this->model->{$f['name']} != Request::input($f['name'])){
						$this->model->{$f['name']} = Request::input($f['name']);

						//If Hash for encrypt or password
						if (isset($f['hash']) && $f['hash'] == 'y'){
							$this->model->{$f['name']} = Hash::make(Request::input($f['name']));
						}

						//For Make Alias
						if(isset($f['alias'])){
							$this->model->{$f['alias']} = $this->build_alias($this->model->{$f['name']});
						}
					}
				}
			}

			$this->model->upd_by = auth()->guard($this->guard)->user()->id;
			$this->model->updated_at 	= date('Y-m-d H:i:s');
			$this->model->save();
			$primary = $this->primary_field;
			Alert::success('Successfully edit '.$this->title.' '.$this->model->$primary);
			return "next";
		}
	}

	public function build_view(){
		// $this->model = $this->model->firstorfail();
		$this->model = $this->model;
		if(in_array($this->model->id,$this->restrict_id)){
			Alert::fail("You don't have access");
			return "exit";
		}
		$this->head_button .= view($this->view_path.'.builder.link',['url' => $this->data['path'],'label' => '<i class="fa fa-arrow-left"></i> '.trans('general.back'),'class' => 'red-mint']);
		$content	= '';
		if(count($this->tab_data) == 0){
			foreach($this->field as $f){
				if (!isset($f['value'])){
					$f= array_add($f,'value',old($f['name']) ? old($f['name']) : $this->model->{$f['name']});
				}
				if(isset($f['format'])){
					if($f['format']['datetime']){
						$f['value'] = date_format(date_create($f['value']),$f['format']['datetime']);
					}
				}
				$content .= $this->build_input($f);
			}
		}else{
			$tab_data = $this->tab_data;
			$content .= '<div class="tabbable-line"><ul class="nav nav-tabs">';
			$tab_loop = 0;
			foreach($tab_data as $td => $tdq){
				if($tab_loop++ == 0){
					$content .= '<li class="active">';
				}else{
					$content .= '<li>';
				}
				$content .= '<a href="#'.$td.'" data-toggle="tab" aria-expanded="true">'.$tdq.'</a>';
				$content .= '</li>';

				$content_tab[$td] = '';
			}
			$content_tab['all'] 	= '';
			$content .= '</ul><div class="tab-content">';

			foreach($this->field as $f){
				if (!isset($f['value'])){
					$f= array_add($f,'value',old($f['name']) ? old($f['name']) : $this->model->{$f['name']});
				}
				if(isset($f['format'])){
					if($f['format']['datetime']){
						$f['value'] = date_format(date_create($f['value']),$f['format']['datetime']);
					}
				}
				if(isset($f['tab'])){
					$content_tab[$f['tab']] .= $this->build_input($f);
				}else{
					$content_tab['all'] 	.= $this->build_input($f);
				}
			}

			$tab_loop = 0;
			foreach($tab_data as $td => $tdq){
				if($tab_loop++ == 0){
					$content .= '<div class="tab-pane active" id="'.$td.'">';
				}else{
					$content .= '<div class="tab-pane" id="'.$td.'">';
				}
				$content .= $content_tab[$td];
				$content .= '</div>';
			}
			$content .= '</div></div>';
			$content .= $content_tab['all'];
		}
		$content .= '<div class="clearfix"></div>';
		$pk_field = $this->primary_field;
		$this->data['title'] = 'View '.$this->title.' '.$this->model->$pk_field;

		$this->data['head_button'] 	= $this->head_button;
		$this->data['content'] 		= $content;
		$this->scripts				.= "$('input,select,textarea,.single-image,.remove-single-image').prop('disabled',true);tinymce.settings = $.extend(tinymce.settings, { readonly: 1 });";
		$this->data['scripts'] 		= $this->scripts;
		return 'next';
	}

	public function build_delete(){
		$secure = DB::transaction(function(){
			$query = $this->model->where('id',Request::input('id'))->first();
			//Check Relation for delete
			if(isset($this->check_relation)){
				$temp = '';
				foreach($this->check_relation as $d){
					$q = count($query->$d);
					if ($q > 0){
						$temp .= ucwords(str_replace('_',' ',$d)).', '; 
					}
				}
				if($temp != ''){
					Alert::fail('You must delete related data first in: '.trim($temp,", "));
					return;
				}
			}
			//Delete Relation with raw
			if(isset($this->delete_relation)){
				foreach($this->delete_relation as $d){
					$query->$d()->delete();
				}
			}
			//Delete File/image
			if (isset($this->field)){
				foreach($this->field as $f){
					if ($f['type'] == 'file'){
						File::delete($f['file_opt']['path'].$query->{$f['name']});
					}
				}
			}
			$query->delete();
			Alert::success('Successfully delete '.$this->title);
			return 'success';
		});
	}

	public function build_single_sorting(){
		$this->head_button .= view($this->view_path.'.builder.link',['url' => $this->data['path'],'label' => '<i class="fa fa-arrow-left"></i> '.trans('general.back'),'class' => 'red-mint']);
		$query = $this->model->get();
		$content  = view($this->view_path.'.includes.errors');
		$content .= "<form method='post' action='".url($this->data['path'])."/ext/dosorting'><ul class='sortable'>";
		$content .= csrf_field();
		foreach($query as $q){
			$content .= '<li class="ui-state-default" '.(count($query) < 5 ? "style=width:100%" : "").'> <input type="hidden" name="'.$this->order_field.'[]" value="'.$q->id.'">';
			foreach($this->field as $f){
				if($f['type'] == 'image'){
					if($q->{$f['name']}){
						if(isset($f['file_opt']['custom_path'])){
							$q->{$f['name']} = asset($f['file_opt']['path'].$q->{$f['file_opt']['custom_path']}.'/'.$q->{$f['name']});
						}else{
							$q->{$f['name']} = asset($f['file_opt']['path'].$q->{$f['name']});
						}
					}else{
						$q->{$f['name']} = asset('components/both/images/web/none.png');
					}
					$content .= "<img src='".$q->{$f['name']}."' width='80px' height='50px'><div class='clearfix'></div>";
				}else{
					$content .= $q->{$f['name']};
				}
			}
			$content .= '</li>';
		}
		$content .= "</ul><div class='clearfix'></div><br/>";
		$content .= view($this->view_path.'.builder.button',['type' => 'submit','label' => '<i class="fa fa-floppy-o"></i> Submit','class' => 'btn-block']);
		$content .= "</form>";
		$this->data['title'] = trans('general.sorting').' '.$this->title;
		$this->data['head_button'] = $this->head_button;
		$this->data['content'] = $content;
	}

	public function build_multiple_sorting(){
		$this->head_button .= view($this->view_path.'.builder.link',['url' => $this->data['path'],'label' => '<i class="fa fa-arrow-left"></i> '.trans('general.back'),'class' => 'red-mint']);
		$content = view($this->view_path.'.includes.errors');;
		$content .= '<form method="get" action="'.url(Request::url()).'">';
		$value = Request::has($this->order_filter['name']) ? Request::input($this->order_filter['name']) : '0';
		$content .= view($this->view_path.'.builder.select',['label' => $this->order_filter['label'],'data' => $this->order_filter['data'],'value' => $value,'class' => 'submit_form','name' => $this->order_filter['name']]);
		$content .= '</form>';	
		if(Request::has($this->order_filter['name'])){
			if($this->query_method == 'raw'){
				$query = $this->model->get();
			}else{
				$query = $this->model->where($this->order_filter['name'],Request::input($this->order_filter['name']))->get();
			}
			$content .= "<form method='post' action='".url($this->data['path'])."/ext/dosorting?".$this->order_filter['name']."=".Request::input($this->order_filter['name'])."'><ul class='sortable'>";
			$content .= $this->hidden(['name' => '_token','value' => $this->generate_token()]);
			foreach($query as $q){
				$content .= '<li class="ui-state-default" '.(count($query) < 5 ? "style=width:100%" : "").'> <input type="hidden" name="'.$this->order_field.'[]" value="'.$q->id.'">';
				foreach($this->field as $f){
					if($f['type'] == 'image'){
						if($q->{$f['name']}){
							if(isset($f['file_opt']['custom_path'])){
								$q->{$f['name']} = asset($f['file_opt']['path'].$q->{$f['file_opt']['custom_path']}.'/'.$q->{$f['name']});
							}else{
								$q->{$f['name']} = asset($f['file_opt']['path'].$q->{$f['name']});
							}
						}else{
							$q->{$f['name']} = asset('components/both/images/web/none.png');
						}
						$content .= "<img src='".$q->{$f['name']}."' width='60px' height=''><div class='clearfix'></div>";
					}else{
						$content .= $q->{$f['name']};
					}
				}
				$content .= '</li>';
			}
			$content .= "</ul><div class='clearfix'></div><br/>";
			if($query->count() > 0){
				$content .= view($this->view_path.'.builder.button',['type' => 'submit','label' => '<i class="fa fa-floppy-o"></i> Submit']);
			}
			$content .= "</form>";
		}
		$this->data['title'] = 'Sorting '.$this->title;
		$this->data['head_button'] = $this->head_button;
		$this->data['content'] = $content;
	}

	public function build_do_sorting(){
		$data = Request::input($this->order_field);
		$query = $this->model;
		for($i = 0; $i<count($data);$i++){
			if($this->query_method == 'raw'){
				$query->where('id',$data[$i])->update(array($this->order_field => $i+1));
			}else{
				if ($this->order_method == 'multiple'){
					$query->where($this->order_filter['name'],Request::input($this->order_filter['name']));
				}
				$query->where('id',$data[$i])->update(array($this->order_field => $i+1));
			}
		}
		Alert::success('Successfully update '.$this->title.' order');
	}

	public function build_report(){
		$data = $this->datareport;
		Report::setdata($data['data']);
		Report::settitle($data['title']);
		Report::setview($data['view']);
		Report::settype($data['type']);
		Report::setformat($data['format']);
		Report::setcreator(auth()->guard($this->guard)->user()->email);
		Report::generate();
		return true;
	}

	public function build($url = ''){
		$this->data['role'] = App('role');
		switch($url){
			case 'index':
				$this->build_index();
				return $this->render_view('builder');
				break;
			case 'create':
				$this->build_create();
				return $this->render_view('builder');
				break;
			case 'store':
				$result = $this->build_store();
				if($result == 'next'){
					return $this->redirect_to($this->data['path']);
				}else{
					return redirect()->back()
						->withInput(Request::except('password'))
						->withErrors($this->build_validation());
				}
				break;
			case 'edit':
				$result 	= $this->build_edit();
				if($result == 'next'){
					return $this->render_view('builder');
				}else{
					return redirect(url($this->data['path']));
				}
				break;
			case 'update':
				$result = $this->build_update();
				if($result == 'next'){
					return redirect()->back();
				}else{
					return redirect()->back()
						->withInput()
						->withErrors($this->build_validation());
				}
				break;
			case 'view':
				$result 	= $this->build_view();
				if($result == 'next'){
					return $this->render_view('builder');
				}else{
					return redirect()->back();
				}
				break;
			case 'delete':
				$this->build_delete();
				return redirect()->back();
				break;
			case 'sorting':
				if ($this->order_method == 'single'){
					$this->build_single_sorting();
				}else{
					$this->build_multiple_sorting();
				}
				return $this->render_view('builder');
				break;
			case 'dosorting':
				$this->build_do_sorting();
				return redirect()->back();
				break;
			case 'report':
				$this->build_report();
				return redirect()->back();
				break;
			default:
				echo "Method Failed";
				break;
		}
	}
}