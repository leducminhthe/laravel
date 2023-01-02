<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{trans('backend.move_training_process')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <form action="{{route('module.movetrainingprocess.submit')}}" method="post" class="form-ajax" id="form-move-training-process" data-success="success_submit">
                @csrf
                <input type="hidden" name="user_old" value="{{$profile_old->user_id}}" />
                <input type="hidden" name="user_new" value="{{$profile_new->user_id}}" />
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <strong>Thông tin nhân viên chuyển ({{ trans('latraining.employee_code') }} {{$profile_old->code}}): </strong><br>
                        Họ tên: {{$profile_old->full_name}}<br>
                        Ngày sinh: {{get_date($profile_old->dob)}}<br>
                        {{ trans('latraining.title') }}: {{$profile_old->title_name}}<br>
                        {{ trans('lamenu.unit') }}: {{$profile_old->unit_name}}
                    </div>
                    <div class="col-sm-6">
                        <strong>Thông tin nhân viên nhận ({{ trans('latraining.employee_code') }} {{$profile_new->code}}): </strong><br>
                        Họ tên: {{$profile_new->full_name}}<br>
                        Ngày sinh: {{get_date($profile_new->dob)}}<br>
                        {{ trans('latraining.title') }}: {{$profile_new->title_name}}<br>
                        {{ trans('lamenu.unit') }}: {{$profile_new->unit_name}}
                    </div>
                </div>
                <br>
                <table class="tDefault table table-hover modal-bootstrap-table text-nowrap">
                    <thead>
                    <tr>
                        <th data-sortable="false" data-align="center" data-formatter="stt_formatter" data-width="25">{{ trans('latraining.stt') }}</th>
                        <th data-field="course_code" data-width="180px">{{ trans('latraining.course_code') }}</th>
                        <th data-field="course_name" >{{ trans('latraining.course_name') }}</th>
                        <th data-field="start_date" data-align="center" data-width="130px">{{ trans('latraining.start_date') }}</th>
                        <th data-field="end_date"  data-align="center" data-width="130px">{{ trans('latraining.end_date') }}</th>
                        <th data-field="process_type" data-width="200px" >{{ trans('backend.form') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                @if ($exists)
                    <button type="submit" class="btn button-save"><i class="fa fa-location-arrow" aria-hidden="true"></i> {{ trans('backend.move_training_process') }}</button>
                @endif
                <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> {{ trans('labutton.close') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function success_submit(form) {
        $("#app-modal #myModal").modal('hide');
        table.refresh();
    }
    var table = new LoadBootstrapTable({
        table: '.modal-bootstrap-table',
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.movetrainingprocess.training_process_old.getData',['user_id'=>$user_id]) }}',
    });
</script>

