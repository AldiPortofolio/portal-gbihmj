@extends($view_path.'.layouts.master')
@push('css')
<style type="text/css">
	.btn_check_array{
		/* display: inline-block; */
	}
	.check_result{
		display: none;
		height: 100%;
		margin-bottom: 10px;
		box-shadow: unset;
	}
	.single-file_wrapper{
    	font-weight: bold;
	}
</style>
@endpush
@section('content')
<form class="showLoading" role="form" method="post" action="{{url($path)}}/{{$data->id}}" enctype="multipart/form-data">
	 {{  method_field('PUT') }}
	<div class="portlet light bordered">
    	<div class="portlet-title">
			<div class="caption font-green">
				<i class="icon-layers font-green title-icon"></i>
				<span class="caption-subject bold uppercase"> {{$title}}</span>
			</div>
			<div class="actions">
				<a href="{{url($path)}}"><button type="button" class="btn red-mint">{{trans('general.back')}}</button></a>
			</div>
    	</div>
    	<div class="portlet-body form">
      		@include('admin.includes.errors')
      		<div class="row">				
			  	<div class="form-group form-md-line-input col-md-12">
					<label>File <span class="required" aria-required="true"></span></label><br>
					<label class="btn green input-file-label-image">
						<input type="file" class="form-control col-md-12 single-file" name="file" value="{{$file_path.$data->file}}"> Pilih File
							</label>
							<button type="button" class="btn red-mint remove-single-file" data-id="single-file" data-name="file">Hapus</button>
						<input type="hidden" name="remove-single-file-file" value="n">
						<br>
					<small>Note: File Must pdf,docx</small>
				</div>

				<div class="form-group single-file_wrapper single-file-file col-md-12"><a href="{{$base_url.'/'.$file_path.'/'.$data->file}}" target="_blank">{{$data->file}}</a></div>
			</div>
			{!!view($view_path.'.builder.button',['type' => 'submit', 'class' => 'btn green btn_submit','label' => trans('general.submit'),'ask' => 'n'])!!}
			</div>
	</div>
</form>
@push('custom_scripts')
	<script>
		$(document).ready(function(){
			$("#article_date").datepicker({
		    	changeMonth: true,
		    	changeYear: true,
		    	dateFormat: 'dd-mm-yy',
		    	yearRange: "0:+90",
		    	showButtonPanel: true,

              	onSelect: function(dateText, inst) {

              	}
          	});

			$('.status_banner').on('click', function(e){
				if($(".status_banner:checked").length > 0){
					$(this).val('y');
				}else{
					$(this).val('n');
				}
			});
		});
	</script>
@endpush
@endsection