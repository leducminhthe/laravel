<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title font-weight-bold">{{trans('latraining.info')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @if (!empty($created_at))
                    <div class="form-group row mb-0">
                        <label class="col-md-4">
                            <h5>Ngày tạo</h5>
                        </label>
                        <div class="col-md-8">
                            {{ $created_at }}
                        </div>
                    </div>
                @endif
                @if (!empty($updated_at))
                    <div class="form-group row mb-0">
                        <label class="col-md-4">
                            <h5>Ngày sửa</h5>
                        </label>
                        <div class="col-md-8">
                            {{ $updated_at }}
                        </div>
                    </div>
                @endif
                <h5>Người tạo</h5>
                <div class="form-group row mb-0">
                    <label class="col-md-4">{{trans('backend.employee_code')}}</label>
                    <div class="col-md-8">
                        {{ $user_create->code }}
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <label class="col-md-4">{{ trans('backend.employee_name') }}</label>
                    <div class="col-md-8">
                        {{ $user_create->full_name }}
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <label class="col-md-4">{{ trans('latraining.title') }}</label>
                    <div class="col-md-8">
                        {{ $user_create->title_name }}
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <label class="col-md-4">{{ trans('lamenu.unit') }}</label>
                    <div class="col-md-8">
                        {{ $user_create->unit_name }}
                    </div>
                </div>

                <h5>Người Sửa</h5>
                <div class="form-group row mb-0">
                    <label class="col-md-4">{{trans('backend.employee_code')}}</label>
                    <div class="col-md-8">
                        {{ $user_update->code }}
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <label class="col-md-4">{{ trans('backend.employee_name') }}</label>
                    <div class="col-md-8">
                        {{ $user_update->full_name }}
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <label class="col-md-4">{{ trans('latraining.title') }}</label>
                    <div class="col-md-8">
                        {{ $user_update->title_name }}
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <label class="col-md-4">{{ trans('lamenu.unit') }}</label>
                    <div class="col-md-8">
                        {{ $user_update->unit_name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

