<div class="tab-pane fade show active" id="nav-about" role="tabpanel">
    <div class="row mb-5" id="user-info">
        <div class="col-xl-3 col-lg-8 pl-0 pr-1">
            <div class="fcrse_2">
                <div class="info-avatar p-0">
                    <a href="javascript:void(0)" id="change-avatar">
                        <img src="{{ image_file(\App\Models\Profile::avatar()) }}" alt="">
                    </a>
                </div>
                <div class="tutor_content_dt mb-0">
                    <div class="mt-2">
                        <a href="#" class="tutor_name">{{ $user->lastname." ".$user->firstname }} </a>
                        @if($user->user_id > 2)
                        <div class="mef78" title="Verify">
                            <a href="javascript:void(0)" id="change-pass"> ({{ trans('laprofile.change_pass') }})</a>
                        </div>
                        @endif
                    </div>
                    @if($promotion)
                        @if(!empty($promotion_level))
                            <h5 class="rainbow rainbow_text_animated mt-3">{{ $promotion_level->name }}</h5>
                        @endif
                        <div class="tutor_cate" style="margin-bottom: 10px;">
                            <div id="shadowBox">
                                <div class="title-name">
                                    <span class="point">{{ $promotion->point }}</span>
                                    <img class="img_point" src="{{ asset('images/level/point.png') }}" alt="">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @if(!empty($promotion_level))
                <div class="fcrse_2">
                    <div class="info-avatar p-0">
                        <img src="{{ image_file($promotion_level->images) }}" alt="">
                    </div>
                </div>
            @endif
        </div>
        <div class="col-xl-9 col-lg-8 pl-1 pr-0">
            <div class="fcrse_2">
                <div class="_htg451">
                    <div class="_htg452">
                        <ul class="_ttl120_custom row m-0">
                            <li class="w-auto col-lg-3 col-sm-12 m-auto p-0">
                                <div class="visible-print text-center mr-2 mt-1" id="show_modal_qrcode">
                                    {!! QrCode::size(120)->generate($info_qrcode); !!}
                                    <p class="qr_scan">@lang('laprofile.scan_code_infomation')</p>
                                </div>
                            </li>
                            <li class="col-lg-4 col-sm-12 pl-1 pr-1">
                                <div class="_ttl121_custom">
                                    <div class="_ttl123_custom mt-0">@lang('laprofile.user_name'):
                                        <span class="_ttl122_custom">
                                            {{ $user_name }}
                                        </span>
                                    </div>
                                    <div class="_ttl123_custom">@lang('laprofile.employee_code'):
                                        <span class="_ttl122_custom">
                                            {{ $user->code }}
                                        </span>
                                    </div>
                                    <div class="_ttl123_custom">@lang('laprofile.full_name'):
                                        <span class="_ttl122_custom">
                                            {{ $user->lastname .' '. $user->firstname }}
                                        </span>
                                    </div>
                                    <div class="_ttl123_custom">@lang('laprofile.dob'):
                                        <span class="_ttl122_custom">
                                            {{ get_date($user->dob) }}
                                        </span>
                                    </div>
                                    <div class="_ttl123_custom">@lang('laprofile.gender'):
                                        <span class="_ttl122_custom">
                                            {{ $user->gender == 1 ? trans('laprofile.male') : trans('laprofile.female') }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                            <li class="col-lg-5 col-sm-12 pl-1 pr-1">
                                <div class="_ttl121_custom">
                                    <div class="_ttl123_custom mt-0">
                                        @lang('lacategory.unit'):
                                        <span class="_ttl122_custom">
                                            @if(isset($user->unit_name)) {{ $user->unit_name }} @endif
                                        </span>
                                    </div>
                                    <div class="_ttl123_custom">@lang('laprofile.title'):
                                        <span class="_ttl122_custom">
                                            @if(isset($user->title_name)) {{ $user->title_name }} @endif
                                        </span>
                                    </div>
                                    <div class="_ttl123_custom">@lang('laprofile.day_work'):
                                        <span class="_ttl122_custom">
                                            {{ get_date($user->join_company) }}
                                        </span>
                                    </div>
                                    <div class="_ttl123_custom">
                                        @lang('laprofile.phone'):
                                        <span class="_ttl122_custom">
                                            {{ $user->phone }}
                                        </span>
                                    </div>
                                    <div class="_ttl123_custom">
                                        {{ trans('laprofile.email') }}:
                                        <span class="_ttl122_custom">
                                            {{ $user->email }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <table class="tDefault table table-hover bootstrap-table">
        <thead>
            <tr>
                <th data-formatter="index_formatter" data-align="center" data-width="5%">#</th>
                <th data-field="user_medal">{{ trans('lamenu.usermedal_setting') }}</th>
                <th data-field="datecreated" data-align="center" data-width="10%">{{ trans('laother.achieved_date') }}</th>
                <th data-field="submedal_name">{{ trans('lamenu.user_level_setting') }}</th>
                <th data-field="image_submedal" data-align="center" data-width="15%">{{ trans('laother.badge_image') }}</th>
                <th data-field="submedal_rank" data-align="center" data-width="10%">{{ trans('laother.rating') }}</th>
            </tr>
        </thead>
    </table>
</div>

<div id="modal-change-avatar" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form action="{{ route('module.frontend.user.change_avatar') }}" method="post" id="form-change-avatar" enctype="multipart/form-data" class="form-ajax">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('laprofile.change_avatar') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <div class="show-demo">
                        <img src="" width="100"/>
                    </div>
                    <div class="text-center">
                        <input type="file" name="selectavatar" accept="image/*">
                        <br/><em>{{ trans('laprofile.size') }}: 100x100px</em>
                    </div>
                    <div id="error-msg" class="alert-danger">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn">{{trans('labutton.save')}}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="modal-change-pass" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form action="{{ route('module.frontend.user.change_pass') }}" method="post" id="form-change-pass" enctype="multipart/form-data" class="form-ajax">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('laprofile.change_pass')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="password_old">@lang('laprofile.old_password')</label>
                        </div>
                        <div class="col-md-9">
                            <input name="password_old" id="password-old" type="password" class="form-control" value="" placeholder="@lang('laprofile.old_password')" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="password">@lang('laprofile.new_password')</label>
                        </div>
                        <div class="col-md-9">
                            <input name="password" id="password" type="password" class="form-control" value="" placeholder="Mật khẩu" autocomplete="off" required>
                            <p></p>
                            <input name="repassword" id="repassword" type="password" class="form-control" value="" placeholder="@lang('laprofile.confirm_password')" autocomplete="off" required>
                            <p></p>
                            <span class="text-danger">{{ trans('laother.noted') }}: {{ trans('laother.noted_pass_capital_letter') }}</span>
                        </div>
                    </div>
                    <div id="error-msg-pass" class="alert-danger">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn">{{trans('labutton.save')}}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="modal-change-info" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('module.frontend.user.change_info') }}" method="post" id="form-change-info" enctype="multipart/form-data" class="form-ajax">
            @csrf
            <input type="hidden" name="key" value="">
            <input type="hidden" name="value_old" value="">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('laother.change_info') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="value_new">{{ trans('latraining.old_value') }}</label>
                        </div>
                        <div class="col-md-8" id="value-old"></div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="value_new">{{ trans('laother.value_change') }}</label>
                        </div>
                        <div class="col-md-8">
                            <textarea name="value_new" id="value-new" type="text" class="form-control" placeholder="{{ trans('laother.value_change') }}" autocomplete="off" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="value_new">{{ trans('latraining.note') }}</label>
                        </div>
                        <div class="col-md-8">
                            <textarea name="note" id="note" type="text" class="form-control" placeholder="{{ trans('latraining.note') }}" autocomplete="off"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn">{{trans('labutton.save')}}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="modal-qrcode" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('laother.your_qr_code') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center w-auto">
                @php
                    $size = isMobile() ? 250 : 270;
                    $padding_top = isMobile() ? 175 : 165;
                @endphp
                <div style="background: url({{ asset('images/khung_qr_code.png') }}) no-repeat center; background-size: contain; height: 600px; padding-top: {{$padding_top}}px;">
                    {!! QrCode::size($size)->generate($info_qrcode); !!}
                </div>
                <p class="qr_scan mt-2">@lang('laprofile.scan_code_infomation')</p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.backend.user_medal.getdata', ['user_id' => profile()->user_id]) }}',
    });

    $('#user-info').on('click', '.change-info', function (event) {
        event.preventDefault();
        var key = $(this).data('key');
        var value_old = $(this).data('value-old');

        $('#modal-change-info #value-old').text(value_old).trigger('change');
        $('#modal-change-info input[name=value_old]').val(value_old).trigger('change');
        $('#modal-change-info input[name=key]').val(key).trigger('change');

        $('#modal-change-info').modal();
        return false;
    });

    $("#change-avatar").on('click', function (event) {
        event.preventDefault();
        $("#modal-change-avatar").modal();
        return false;
    });

    $("#change-pass").on('click', function (event) {
        event.preventDefault();
        $("#modal-change-pass").modal();
        return false;
    });

    $('#show_modal_qrcode').on('click', function(event){
        event.preventDefault();
        $("#modal-qrcode").modal();
        return false;
    });
</script>
