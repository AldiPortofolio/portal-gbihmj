@extends($view_path.'.layouts.master')
@section('content')
<form role="form" method="post" action="{{url($path)}}/" enctype="multipart/form-data">
  {{ method_field('PUT') }}
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption font-green">
        <i class="icon-layers font-green title-icon"></i>
        <span class="caption-subject bold uppercase"> {{$title}}</span>
      </div>
      <div class="actions">
        <div class="actions">
          <a href="{{url($path)}}"><button type="button" class="btn red-mint">{{trans('general.back')}}</button></a>
        </div>
      </div>
    </div>
    <div class="portlet-body form">
      <div class="table-responsive">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <th>No</th>
              <th>Sender</th>
              <th></th>
            </tr>

            @foreach($inbox as $q => $ibx)
            <tr>
              <td class="vcenter">{{ $q+1 }}</td>
              <td class="vcenter">{{ $ibx->sender_name }}</td>
              <td class="vcenter">
                <a href="{{ url($path) }}/{{ $ibx->sender }}" class="btn green green-jungle">
                  View Inbox
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <center>{{ $inbox->links() }}</center>
    </div>
  </div>
</form>
@endsection
