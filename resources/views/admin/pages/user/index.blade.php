@extends($view_path.'.layouts.master')
@section('content')
<div class="row">
    <!-- <a href={{url($path)}}/create id="datatable-create" class="btn blue btn-outline btn-circle"><i class="fa fa-plus"></i> Create</a>
</div>
<div class="row">
    <table style="width:100%">
        <tr>
            <th>No</th>
            <th>Username</th> 
            <th>Email</th>
            <th>Status</th>
            <th colspan="3"></th>
        </tr>
        @for ($i = 0;$i < count($user); $i++)
            <tr>
                <td>{{$i + 1}}</td>
                <td>{{$user[$i]->username}}</td>
                <td>{{$user[$i]->email}}</td> 
                <td>{{$user[$i]->status}}</td>
                <td><a href="{{url($path)}}/{{$user[$i]->id}}" class="btn green ">
                        <i class="fa fa-eye"></i> View
                    </a>
                </td>
                <td><a href="{{url($path)}}/{{$user[$i]->id}}/edit" class="btn green ">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                </td>
                <td>
                    <button type="submit" class="btn green red-mint sweet-alert-confirmation"> <i class="fa fa-trash"></i> Delete</button>
                </td>
            </tr>
        @endfor
    </table> -->
</div>
@endsection