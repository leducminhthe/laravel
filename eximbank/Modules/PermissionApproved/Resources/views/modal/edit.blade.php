<div class="modal fade"  id="modal-permission-approved" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <form id="frm-save-approve" class="form-horizontal" method="post" action="{{route('backend.permission.approved.update',['id'=>$id])}}" role="form" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title">Chỉnh sửa phê duyệt cấp {{$level}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="idapproved" id="idapproved" value="">
                    <div>
                        <div class="form-group">
                            <label>{{ trans('latraining.approve_level') }}</label>
                            <select id="objectlevel" name='objectlevel' class="form-control">
                                <option value="0">{{ trans('latraining.select_level_approve') }}</option>
                                @foreach ($objects as $object)
                                    <option value="{{$object->id}}" {{$object->id==$object->object_id?'selected':''}}>{{$object->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="form-employees">
                            <label>{{ trans('lamenu.user') }}</label>
                            <select name="employees" id="employees" multiple class="form-control load-user" data-placeholder="{{ trans('laprofile.enter_code_name_user') }}">
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}" selected>{{$user->full_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" id="form-titles">
                            <label>{{ trans('latraining.title') }}</label>
                            <select name="titles" id="titles" class="form-control load-title" multiple data-placeholder="{{ trans('latraining.title') }}">
                                @foreach ($titles as $title)
                                    <option value="{{$title->id}}" selected>{{$title->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" name="approve_all_child" id="approve_all_child" value="{{ $approve_all_child }}" {{ $approve_all_child == 1 ? 'checked' : '' }}>
                            <label for="approve_all_child">Duyệt phủ quyền</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('labutton.close')}}</button>

                    <button id="update-approved" class="btn"><i class="fa fa-save"></i> {{trans('labutton.save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('#modal-permission-approved').on('click', '#approve_all_child', function(){
        if($(this).is(':checked')){
            $('#modal-permission-approved #approve_all_child').val(1);
        }else{
            $('#modal-permission-approved #approve_all_child').val(0);
        }
    });
</script>
