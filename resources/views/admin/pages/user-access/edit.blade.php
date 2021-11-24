@extends($view_path.'.layouts.master')
@section('content')
<div class="portlet light bordered">
  <div class="portlet-title">
      <div class="caption font-green portlet-container">
        <i class="icon-layers font-green title-icon"></i>
        <span class="caption-subject bold uppercase"> {{$title}}</span>
        <div class="head-button">
          <a href="{{url($path)}}"><button class="btn red-mint">{{trans('general.back')}}</button></a>
        </div>
      </div>
  </div>
  <div class="portlet-body form">
    <form role="form" method="post" action="{{url($path)}}/{{$useraccess->id}}" enctype="multipart/form-data">
      {{ method_field('PUT') }}
      @include('admin.includes.errors')
      <div class="form-group">
         {!!view($view_path.'.builder.text',['name' => 'access_name','label' => 'Access Name','attribute' => 'required','value' => $useraccess->access_name])!!}
      </div>
      <div class="form-group">
        <button type="button" class="btn btn-primary checkall" data-target="access">Checkall</button>
        <button type="button" class="btn btn-primary uncheckall" data-target="access">Uncheckall</button>
      </div>
      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td>Menu</td>
                <td align="center">Active</td>
                <td align="center">View</td>
                <td align="center">Create</td>
                <td align="center">Edit</td>
                <td align="center">Delete</td>
                <td align="center">Sorting</td>
                <td align="center">Export</td>
              </tr>
            </thead>
            <tbody>
              @foreach($prmenu as $pr)
                <tr>
                  <td><b>{{$pr->menu_name}}</b></td>
                  <td align="center">
                    <div class="form-md-checkboxes">
                      <div class="md-checkbox-inline">
                        <div class="md-checkbox">
                          <input type="checkbox" id="checkbox_form_{{$pr->id}}" class="md-check access" name="fkuseraccessid[]" {{array_key_exists($pr->id,$dataau) ? 'checked' : ''}}  {{$pr->id == '1' ? 'required checked' : ''}} value="{{$pr->id}}">
                          <label for="checkbox_form_{{$pr->id}}">
                            <span></span>
                            <span class="check"></span>
                            <span class="box"></span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td colspan="6"></td>
                </tr>
                @if(count($chmenu[$pr->id]) > 0)
                  @foreach ($chmenu[$pr->id] as $ch)
                    <tr>
                      <td>
                        <div style="padding-left:10%;">
                          {{$ch->menu_name}}
                        </div>
                      </td>
                      <td align="center">
                        <div class="form-group form-md-checkboxes">
                          <div class="md-checkbox-inline">
                            <div class="md-checkbox">
                              <input type="checkbox" id="checkbox_form_{{$pr->id}}_{{$ch}}" class="md-check access" name="fkuseraccessid[]" {{isset($dataau[$ch->id]) ? ($ch->id == $dataau[$ch->id]->menu_id ? 'checked' : '') : ''}} value="{{$ch->id}}">
                              <label for="checkbox_form_{{$pr->id}}_{{$ch}}">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span>
                              </label>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td align="center">
                         @if($ch->view == 'y')
                          <div class="form-md-checkboxes">
                            <div class="md-checkbox-inline">
                              <div class="md-checkbox">
                                <input type="checkbox" id="checkbox_form_{{$pr->id}}_{{$ch->id}}_v" class="md-check access" name="{{$ch->id}}-v" {{isset($dataau[$ch->id]) ? ($dataau[$ch->id]->view == 'y' ? 'checked' : '') : ''}} value="y">
                                <label for="checkbox_form_{{$pr->id}}_{{$ch->id}}_v">
                                  <span></span>
                                  <span class="check"></span>
                                  <span class="box"></span>
                                </label>
                              </div>
                            </div>
                          </div>
                        @endif
                      </td>
                      <td align="center">
                        @if($ch->create == 'y')
                          <div class="form-md-checkboxes">
                            <div class="md-checkbox-inline">
                              <div class="md-checkbox">
                                <input type="checkbox" id="checkbox_form_{{$pr->id}}_{{$ch->id}}_c" class="md-check access" name="{{$ch->id}}-c" {{isset($dataau[$ch->id]) ? ($dataau[$ch->id]->create == 'y' ? 'checked' : '') : ''}} value="y">
                                <label for="checkbox_form_{{$pr->id}}_{{$ch->id}}_c">
                                  <span></span>
                                  <span class="check"></span>
                                  <span class="box"></span>
                                </label>
                              </div>
                            </div>
                          </div>
                        @endif
                      </td>
                      <td align="center">
                        @if($ch->edit == 'y')
                          <div class="form-md-checkboxes">
                            <div class="md-checkbox-inline">
                              <div class="md-checkbox">
                                <input type="checkbox" id="checkbox_form_{{$pr->id}}_{{$ch->id}}_e" class="md-check access" name="{{$ch->id}}-e" {{isset($dataau[$ch->id]) ? ($dataau[$ch->id]->edit == 'y' ? 'checked' : '') : ''}} value="y">
                                <label for="checkbox_form_{{$pr->id}}_{{$ch->id}}_e">
                                  <span></span>
                                  <span class="check"></span>
                                  <span class="box"></span>
                                </label>
                              </div>
                            </div>
                          </div>
                        @endif
                      </td>
                      <td align="center">
                        @if($ch->delete == 'y')
                          <div class="form-md-checkboxes">
                            <div class="md-checkbox-inline">
                              <div class="md-checkbox">
                                <input type="checkbox" id="checkbox_form_{{$pr->id}}_{{$ch->id}}_d" class="md-check access" name="{{$ch->id}}-d" {{isset($dataau[$ch->id]) ? ($dataau[$ch->id]->delete == 'y' ? 'checked' : '') : ''}} value="y">
                                <label for="checkbox_form_{{$pr->id}}_{{$ch->id}}_d">
                                  <span></span>
                                  <span class="check"></span>
                                  <span class="box"></span>
                                </label>
                              </div>
                            </div>
                          </div>
                        @endif
                      </td>
                      <td align="center">
                        @if($ch->sorting == 'y')
                          <div class="form-md-checkboxes">
                            <div class="md-checkbox-inline">
                              <div class="md-checkbox">
                                <input type="checkbox" id="checkbox_form_{{$pr->id}}_{{$ch->id}}_s" class="md-check access" name="{{$ch->id}}-s" {{isset($dataau[$ch->id]) ? ($dataau[$ch->id]->sorting == 'y' ? 'checked' : '') : ''}} value="y">
                                <label for="checkbox_form_{{$pr->id}}_{{$ch->id}}_s">
                                  <span></span>
                                  <span class="check"></span>
                                  <span class="box"></span>
                                </label>
                              </div>
                            </div>
                          </div>
                        @endif
                      </td>
                      <td align="center">
                        @if($ch->export == 'y')
                          <div class="form-md-checkboxes">
                            <div class="md-checkbox-inline">
                              <div class="md-checkbox">
                                <input type="checkbox" id="checkbox_form_{{$pr->id}}_{{$ch->id}}_ex" class="md-check access" name="{{$ch->id}}-ex" {{isset($dataau[$ch->id]) ? ($dataau[$ch->id]->export == 'y' ? 'checked' : '') : ''}} value="y">
                                <label for="checkbox_form_{{$pr->id}}_{{$ch->id}}_ex">
                                  <span></span>
                                  <span class="check"></span>
                                  <span class="box"></span>
                                </label>
                              </div>
                            </div>
                          </div>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                @endif
              @endforeach
            <tbody>
          </table>
        </div>       
      </div>
      <div class="clearfix"></div><br/>
      <div class="box-footer">
        {!!view($view_path.'.builder.button',['type' => 'submit', 'class' => 'btn green','label' => trans('general.submit'),'ask' => 'n'])!!}
      </div>
    </form>
  </div>
</div>
@if(isset($scripts))
  @push('scripts')
    <script>
      $(document).ready(function(){
        {!!$scripts!!}
      });
    </script>
  @endpush
@endif
@endsection
