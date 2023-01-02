<div class="row">
    <div class="col-md-9">
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="username">{{ trans('laprofile.user_name') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-9">
                <input name="username" id="username" type="text" class="form-control" required autocomplete="off" value="{{ isset($user) ? $user->username: '' }}" {{ isset($user->username) ? 'readonly': '' }}>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="password">{{ trans('laprofile.pass') }}</label>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        <input name="password" id="password" type="password" class="form-control" value="" placeholder="{{ trans('laprofile.pass') }}" autocomplete="off">
                    </div>

                    <div class="col-md-6">
                        <input name="repassword" id="repassword" type="password" class="form-control" value="" placeholder="{{ trans('laprofile.repassword') }}" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="username">{{ trans('laprofile.login_form') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-9">
                <select name="auth" id="auth" class="form-control select2" data-placeholder="-- {{ trans('laprofile.login_form') }} --" required>
                    <option value="manual" {{ (isset($user) && $user->auth == 'manual') ? 'selected' : '' }} >Manual</option>
                    <option value="microsoft" {{ (isset($user) && $user->auth == 'microsoft') ? 'selected' : '' }} >Microsoft</option>
                    <option value="blocked" {{ (isset($user) && $user->auth == 'blocked') ? 'selected' : '' }} >Blocked</option>
{{--                    <option value="ldap" {{ (isset($user) && $user->auth == 'ldap') ? 'selected' : '' }} >LDAP</option>--}}
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="code">{{ trans('laprofile.employee_code') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-9">
                <input name="code" type="text" class="form-control" value="{{ $model->code }}" required>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="">{{ trans('laprofile.they_staff') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-9">
                <input name="lastname" type="text" class="form-control" value="{{ $model->lastname }}" required autocomplete="off">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('laprofile.employee_name') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-9">
                <input name="firstname" type="text" class="form-control" value="{{ $model->firstname }}" required autocomplete="off">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('laprofile.email') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-9">
                <input name="email" type="text" class="form-control" value="{{ $model->email($model->user_id) }}" required>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="position_id">{{ trans('laprofile.position') }}</label>
            </div>
            <div class="col-md-9">
                <select name="position_id" id="position_id" class="form-control load-position" data-placeholder="-- {{ trans('laprofile.position') }} --">
                    @if(isset($position))
                        <option value="{{ $position->id }}"> {{ $position->name }}{{ $position->status==0?" (Đã tắt)":"" }}</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="title_rank_id">{{ trans('lacategory.title_level') }}</label>
            </div>
            <div class="col-md-9">
                <select name="title_rank_id" id="title_rank_id" class="load-title-rank" data-placeholder="-- {{ trans('lacategory.title_level') }} --">
                    @if(isset($title_rank))
                        <option value="{{ $title_rank->id }}">{{ $title_rank->name }}{{ $title_rank->status==0?" (Đã tắt)":"" }}</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="title_id">{{ trans('laprofile.title') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-9">
                <select name="title_id" id="title_id" class="load-title" data-title_rank_id="{{ isset($title) ? $title->group : '' }}" data-placeholder="-- {{ trans('laprofile.title') }} --">
                    @if(isset($title))
                        <option value="{{ $title->id }}">{{ $title->name }}{{ $title->status==0?" (Đã tắt)":"" }}</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="unit_id">{{ trans('lamenu.unit') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-9">
                @if ($model->id)
                    @include('backend.form_choose_unit', ['unit_id' => $model->unit_id, 'unit_name' => $unit_name])
                @else
                    @include('backend.form_choose_unit')
                @endif
            </div>
        </div>
        {{-- <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="unit_id_0">{{ trans('lasetting.company') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-9">
                <select name="unit_id" id="unit_id_0" class="load-unit" data-placeholder="-- {{ trans('lasetting.company') }} --" data-level="0" data-parent="" data-loadchild="unit_id_1">
                    @if(isset($unit[0]))
                        <option value="{{ $unit[0]->id }}">{{ $unit[0]->name }}{{ $unit[0]->status==0?" (Đã tắt)":"" }}</option>
                    @endif
                </select>
            </div>
        </div>
        @for($i=1;$i<=6;$i++)
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="unit_id_{{ $i }}">
                        {{ trans('lacategory.unit_level', ['i' => $i]) }}
                    </label>
                </div>
                <div class="col-md-9">
                    <select name="unit_id" id="unit_id_{{ $i }}" class="load-unit" data-placeholder="-- {{ trans('lacategory.unit_level', ['i' => $i]) }} --" data-level="{{ $i }}" data-parent="{{ empty($unit[$i-1]->id) ? '' : $unit[$i-1]->id }}" data-loadchild="unit_id_{{ $i+1 }}">
                        @if(isset($unit[$i]))
                            <option value="{{ $unit[$i]->id }}">{{ $unit[$i]->name }}{{ $unit[$i]->status==0?" (Đã tắt)":"" }}</option>
                        @endif
                    </select>
                </div>
            </div>
        @endfor --}}

        {{--@for($i=1;$i<=$max_area;$i++)
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="area_id_{{ $i }}">{{ data_locale($level_name_area($i)->name, $level_name_area($i)->name_en) }}</label>
                </div>
                <div class="col-md-9">
                    <select name="area_id" id="area_id_{{ $i }}" class="load-area" data-placeholder="-- {{ data_locale('Chọn', 'Choose') .' '. data_locale($level_name_area($i)->name, $level_name_area($i)->name_en) }} --" data-level="{{ $i }}"
                            data-parent="{{ empty($area[$i-1]->id) ? '' : $area[$i-1]->id }}" data-loadchild="area_id_{{ $i+1 }}">
                        @if(isset($area[$i]))
                            <option value="{{ $area[$i]->id }}">{{ $area[$i]->name }}{{ $area[$i]->status==0?" (Đã tắt)":"" }}</option>
                        @endif
                    </select>
                </div>
            </div>
        @endfor--}}

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="address">{{ trans('laprofile.address') }}</label>
            </div>
            <div class="col-md-9">
                <textarea name="address" id="address" class="form-control" rows="5">{{ $model->address }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="current_address">{{ trans('laprofile.current_address') }}</label>
            </div>
            <div class="col-md-9">
                <textarea name="current_address" id="current_address" class="form-control" rows="5">{{ !is_null($user_meta($model->user_id, 'current_address')) ? $user_meta($model->user_id, 'current_address')->value : '' }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3  control-label">
                <label>{{ trans('laprofile.name_contact_person') }}</label>
            </div>
            <div class="col-md-9">
                <input name="name_contact_person" value="{{ !is_null($user_meta($model->user_id, 'name_contact_person')) ? $user_meta($model->user_id, 'name_contact_person')->value : ''  }}" class="form-control" >
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3  control-label">
                <label>{{ trans('laprofile.relationship') }}</label>
            </div>
            <div class="col-md-9">
                <input name="relationship" value="{{ !is_null($user_meta($model->user_id, 'relationship')) ? $user_meta($model->user_id, 'relationship')->value : ''  }}" class="form-control" >
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3  control-label">
                <label>{{ trans('laprofile.phone_contact_person') }}</label>
            </div>
            <div class="col-md-9">
                <input name="phone_contact_person" value="{{ !is_null($user_meta($model->user_id, 'phone_contact_person')) ? $user_meta($model->user_id, 'phone_contact_person')->value : ''  }}" class="form-control" >
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3  control-label">
                <label>{{ trans('laprofile.school') }}</label>
            </div>
            <div class="col-md-9">
                <input name="school" value="{{ !is_null($user_meta($model->user_id, 'school')) ? $user_meta($model->user_id, 'school')->value : ''  }}" class="form-control" >
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3  control-label">
                <label>{{ trans('laprofile.majors') }}</label>
            </div>
            <div class="col-md-9">
                <input name="majors" value="{{ !is_null($user_meta($model->user_id, 'majors')) ? $user_meta($model->user_id, 'majors')->value : ''  }}" class="form-control" >
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('laprofile.license') }}</label>
            </div>
            <div class="col-md-9">
                <input name="license" value="{{ !is_null($user_meta($model->user_id, 'license')) ? $user_meta($model->user_id, 'license')->value : ''  }}" class="form-control" >
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3  control-label">
                <label>{{ trans('laprofile.suspension_date') }}</label>
            </div>
            <div class="col-md-9">
                <input name="suspension_date" value="{{ !is_null($user_meta($model->user_id, 'suspension_date')) ? $user_meta($model->user_id, 'suspension_date')->value : ''  }}" class="form-control datepicker" >
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="reason">{{ trans('laprofile.reason') }}</label>
            </div>
            <div class="col-md-9">
                <textarea name="reason" id="reason" class="form-control" rows="5">{{ !is_null($user_meta($model->user_id, 'reason')) ? $user_meta($model->user_id, 'reason')->value : '' }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3  control-label">
                <label>{{ trans('laprofile.commendation') }}</label>
            </div>
            <div class="col-md-9">
                <input name="commendation" value="{{ !is_null($user_meta($model->user_id, 'commendation')) ? $user_meta($model->user_id, 'commendation')->value : ''  }}" class="form-control" >
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3  control-label">
                <label>{{ trans('laprofile.discipline') }}</label>
            </div>
            <div class="col-md-9">
                <input name="discipline" value="{{ !is_null($user_meta($model->user_id, 'discipline')) ? $user_meta($model->user_id, 'discipline')->value : ''  }}" class="form-control" >
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3  control-label">
                <label>{{ trans('laprofile.special_skills') }}</label>
            </div>
            <div class="col-md-9">
                <input name="special_skills" value="{{ !is_null($user_meta($model->user_id, 'special_skills')) ? $user_meta($model->user_id, 'special_skills')->value : ''  }}" class="form-control" >
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="note">{{ trans('laprofile.note') }}</label>
            </div>
            <div class="col-md-9">
                <textarea name="note" id="note" class="form-control" rows="5">{{ !is_null($user_meta($model->user_id, 'note')) ? $user_meta($model->user_id, 'note')->value : '' }}</textarea>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="row row-acts-btn mb-2">
            <div class="col-sm-12">
                <div class="btn-group act-btns">
                    @if (!App\Models\User::isRoleLeader())
                        @canany(['user-create', 'user-edit'])
                            <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                        @endcanany
                    @endif
                    <a href="{{ route('module.backend.user') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                </div>
            </div>
        </div>

{{--        <div class="form-group">--}}
{{--            <label class="control-label" for="level">{{ trans('laprofile.rank') }}</label>--}}
{{--            <input name="level" id="level" type="text" class="form-control" value="{{ $model->level }}">--}}
{{--        </div>--}}

        <div class="form-group">
            <label class="control-label" for="gender">{{ trans('laprofile.gender') }} <span class="text-danger">*</span></label>
            <select name="gender" id="gender" class="form-control select2" data-placeholder="-- {{ trans('laprofile.gender') }} --">
                <option value=""></option>
                <option value="1" {{ $model->gender == '1' ? 'selected' : '' }}> {{ trans('laprofile.male') }} </option>
                <option value="0" {{ (!is_null($model->gender) && $model->gender == '0') ? 'selected' : '' }}> {{ trans('laprofile.female') }} </option>
            </select>
        </div>
        <div class="form-group">
            <label class="control-label" for="marriage">{{ trans('laprofile.marital_status') }}</label>
            <select name="marriage" id="marriage" class="form-control select2" data-placeholder="-- {{ trans('laprofile.marital_status') }} --">
                <option value=""></option>
                <option value="1" {{ $model->marriage== 1 ? 'selected' : '' }}> {{ trans('laprofile.married') }} </option>
                <option value="0" {{ is_numeric($model->marriage) && $model->marriage == 0 ? 'selected' : '' }}> {{ trans('laprofile.single') }} </option>
            </select>
        </div>

        <div class="form-group">
            <label class="control-label" for="phone">{{ trans('laprofile.phone') }}</label>
            <input name="phone" id="phone" type="text" class="form-control is-number" value="{{ $model->phone }}">
        </div>

        <div class="form-group">
            <label class="control-label" for="dob">{{ trans('laprofile.dob') }}</label>
            <input name="dob" id="dob" type="text" class="form-control datepicker" autocomplete="off" value="{{ get_date($model->dob) }}">
        </div>

        <div class="form-group">
            <label class="control-label">{{ trans('laprofile.identity_card') }}</label>
            <input name="identity_card" type="text" class="form-control is-number" value="{{ $model->identity_card }}">
        </div>

        <div class="form-group">
            <label class="control-label">{{ trans('laprofile.date_issue') }}</label>
            <input name="date_range" type="text" class="form-control datepicker" autocomplete="off" value="{{ get_date($model->date_range) }}">

        </div>

        <div class="form-group">
            <label class="control-label">{{ trans('laprofile.issued_by') }}</label>
            <input name="issued_by" type="text" class="form-control" value="{{ $model->issued_by }}">
        </div>

        <div class="form-group">
            <label class="control-label" for="expbank">{{ trans('laprofile.experience') }}</label>
            <input name="expbank" id="expbank" type="text" class="form-control is-number" value="{{ $model->expbank }}">
        </div>

        <div class="form-group">
            <label class="control-label" for="certificate_code">{{ trans('laprofile.level') }} </label>
            <select name="certificate_code" class="form-control">
            @foreach($certs as $item)
                    <option value="{{$item->certificate_code}}" {{$item->certificate_code==$model->certificate_code?'selected':''}} >{{$item->certificate_name}}</option>
            @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="control-label">{{ trans('laprofile.contract_signing_date') }}</label>
            <input name="contract_signing_date" type="text" class="form-control datepicker" autocomplete="off" value="{{ get_date($model->contract_signing_date) }}">
        </div>

        <div class="form-group">
            <label class="control-label">{{ trans('laprofile.effective_date') }}</label>
            <input name="effective_date" type="text" class="form-control datepicker" autocomplete="off" value="{{ get_date($model->effective_date) }}">
        </div>

        <div class="form-group">
            <label class="control-label">{{ trans('laprofile.expiration_date') }}</label>
            <input name="expiration_date" type="text" class="form-control datepicker" autocomplete="off" value="{{ get_date($model->expiration_date) }}">
        </div>

        <div class="form-group">
            <label class="control-label">{{ trans('laprofile.day_work') }}</label>
            <input name="join_company" type="text" class="form-control datepicker" autocomplete="off" value="{{ get_date($model->join_company) }}">
        </div>

        <div class="form-group">
            <label class="control-label">{{ trans('laprofile.date_off') }}</label>
            <input name="date_off" type="text" class="form-control datepicker" autocomplete="off" value="{{ get_date($model->date_off) }}">
        </div>

        <div class="form-group">
            <label class="control-label">{{ trans('laprofile.date_title_appointment') }}</label>
            <input name="date_title_appointment" type="text" class="form-control datepicker" autocomplete="off" value="{{ get_date($model->date_title_appointment) }}">
        </div>

        <div class="form-group">
            <label class="control-label">{{ trans('laprofile.end_date_title_appointment') }}</label>
            <input name="end_date_title_appointment" type="text" class="form-control datepicker" autocomplete="off" value="{{ get_date($model->end_date_title_appointment) }}">
        </div>

        <div class="form-group">
            <label class="control-label" for="type_labor_contract">{{ trans('laprofile.type_labor_contract') }}</label>
            {{-- (0: Thời vụ, 1: Thử việc, 2: Có thời hạn, 3: Không thời hạn)--}}
            <select name="type_labor_contract" id="type_labor_contract" class="form-control select2" data-placeholder="-- {{ trans('backend.type_labor_contract') }} --">
                <option value=""></option>
                <option value="0" {{ (!is_null($user_meta($model->user_id, 'type_labor_contract')) && $user_meta($model->user_id, 'type_labor_contract')->value == 0) ? 'selected' : '' }}> {{ trans('laprofile.part_time') }} </option>
                <option value="1" {{ (!is_null($user_meta($model->user_id, 'type_labor_contract')) && $user_meta($model->user_id, 'type_labor_contract')->value == 1) ? 'selected' : '' }}> {{ trans('laprofile.probationary') }} </option>a
                <option value="2" {{ (!is_null($user_meta($model->user_id, 'type_labor_contract')) && $user_meta($model->user_id, 'type_labor_contract')->value == 2) ? 'selected' : '' }}> {{ trans('laprofile.has_term') }} </option>
                <option value="3" {{ (!is_null($user_meta($model->user_id, 'type_labor_contract')) && $user_meta($model->user_id, 'type_labor_contract')->value == 3) ? 'selected' : '' }}> {{ trans('laprofile.indefinite') }} </option>
            </select>
        </div>

        <div class="form-group">
            <label class="control-label" for="status">{{ trans('laprofile.status') }} <span class="text-danger">*</span></label>

            {{--0: Nghỉ việc, 1: Đang làm (nhân viên chính thức), 2: Thử việc, 3: Tạm hoãn--}}
            <select name="status" id="status" class="form-control select2" data-placeholder="-- {{ trans('laprofile.status') }} --">
                <option value=""></option>
                <option value="0" {{ ( !is_null($model->status) && $model->status == 0) ? 'selected' : '' }}> {{ trans('laprofile.inactivity') }} </option>
                <option value="1" {{ $model->status == 1 ? 'selected' : '' }}> {{ trans('laprofile.doing') }} </option>a
                {{-- <option value="2" {{ $model->status == 2 ? 'selected' : '' }}> {{ trans('backend.probationary') }} </option> --}}
                {{-- <option value="3" {{ $model->status == 3 ? 'selected' : '' }}> {{ trans('backend.pause') }} </option> --}}
            </select>
        </div>
        {{-- <div class="form-group ">
            <label class="control-label">{{ trans('laprofile.your_referral_code') }}</label>
            <input name="my_refer" value="{{$model->id_code}}" readonly style="cursor: pointer;" class="form-control" >
        </div> --}}
    </div>
</div>
<script type="text/javascript">
    $('#title_rank_id').on('change', function () {
        var title_rank_id = $('#title_rank_id option:selected').val();

        $("#title_id").empty();
        $("#title_id").data('title_rank_id', title_rank_id??'');
        $('#title_id').trigger('change');
    })
</script>
