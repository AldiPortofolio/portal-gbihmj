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
	.single-audio_wrapper{
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
				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'title','label' => 'Title','value' => (old('title') ? old('title') : $data->title),'attribute' => 'autofocus required','form_class' => 'col-md-6'])!!}

				{!!view($view_path.'.builder.file',['name' => 'image','label' => 'image','value' => $data->image,'type' => 'file','file_opt' => ['path' => $image_path],'upload_type' => 'single-image','class' => 'col-md-12','note' => 'Note: File Must jpeg,png,jpg,gif','form_class' => 'col-md-12', 'required' => 'y'])!!}
			
			  	<div class="form-group form-md-line-input col-md-12">
					<label>File <span class="required" aria-required="true"></span></label><br>
					<label class="btn green input-file-label-image">
						<input type="file" class="form-control col-md-12 single-audio" name="music_name"> Pilih File
							</label>
							<button type="button" class="btn red-mint remove-single-audio" data-id="single-audio" data-name="music_name">Hapus</button>
						<input type="hidden" name="remove-single-file-music_name" value="n">
						<br>
					<small>Note: File Must mp3</small>
				</div>

				<div class="form-group single-audio_wrapper single-audio-music_name col-md-12">{{$data->music_name}}</div>

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