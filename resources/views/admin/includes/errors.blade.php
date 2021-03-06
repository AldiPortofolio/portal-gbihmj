@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li><i class="fa fa-warning"></i> {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (Session::has('message'))
	{!! Session('message') !!}
@endif
{{csrf_field()}}