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
</style>
@endpush
@section('content')
<form role="form" method="post" action="{{url($path)}}/{{$data->id}}" enctype="multipart/form-data">
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
				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'title','label' => 'Title','value' => (old('title') ? old('title') : $data->title),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'title', 'required' => 'y'])!!}
				
				{!!view($view_path.'.builder.textarea',['type' => 'text','name' => 'content','label' => 'Content','value' => (old('content') ? old('content') : $data->content),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'content', 'required' => 'y'])!!}
				

				{!!view($view_path.'.builder.file',['name' => 'image','label' => 'Image','value' => $data->image,'type' => 'file','file_opt' => ['path' => $image_path],'upload_type' => 'single-image','class' => 'col-md-12','note' => 'Note: File Must jpeg,png,jpg,gif','form_class' => 'col-md-12', 'required' => 'n'])!!}

				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'write_by','label' => 'Write by','value' => (old('write_by') ? old('write_by') : $data->write_by),'attribute' => 'required autofocus','form_class' => 'col-md-12', 'class' => 'write_by', 'required' => 'y'])!!}
				
				{!!view($view_path.'.builder.text',['type' => 'text','name' => 'url','label' => 'URL','value' => (old('url') ? old('url') : $data->url),'attribute' => 'autofocus','form_class' => 'col-md-12', 'class' => 'url', 'required' => 'n'])!!}

				<div class="col-md-6" id="">
		          <div class="form-group form-md-line-input">
		            <input type="text" id="article_date" class="form-control" name="article_date" value="{{$data->article_date != '0000-00-00' ? date_format(date_create($data->article_date), 'd-m-Y') : ''}}" required="" readonly="" placeholder="Event Date">
		            <label for="form_floating_Hqd">Article Date <!--<span class="" aria-required="true">*</span>--></label>
		            <small></small>
		          </div>
		        </div>

				<div class="form-group col-md-12" style="">
				  	<div class="md-checkbox">
				  		<label>Status Banner (Jika ingin menampilkan article di banner)</label>
						<input type="checkbox" id="checkbox_form_1" class="md-check status_banner" name="status_banner" value="{{$data->status_banner}}" {{$data->status_banner  == 'y' ? 'checked' : ''}}>
						<label for="checkbox_form_1">
							<span></span>
					        <span class="check"></span>
					        <span class="box"></span>
						</label>
					</div>
				</div>

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