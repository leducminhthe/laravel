@extends('layouts.backend')

@section('page_title', trans('lamenu.badge'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('latraining.emulation_badge'),
                'url' => route('module.emulation_badge.list')
            ],
            [
                'name' => trans('lacategory.add_new'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <form action="{{ route('module.emulation_badge.save') }}" method="post" class="form-ajax" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6 text-right act-btns">
                <div class="btn-group">
                    @can(['emulation-badge-create', 'emulation-badge-edit'])
                        @if(!$ro)
                        <button type="submit" class="btn"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                        @endif
                    @endcan
                    <a href="{{ route('module.usermedal.list') }}" class="btn"><i class="fa fa-times"></i> {{ trans('labutton.cancel') }}</a>
                </div>
            </div>
        </div>
        <div class="mt-3"></div>
        <div class="tPanel">
            <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#base">{{ trans('lacategory.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div>&nbsp;</div>
                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>{{ trans('lacategory.code') }} <span class="text-danger">*</span> </label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="code" class="form-control" value="{{ $model->code }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>{{ trans('lacategory.name') }} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="name" class="form-control" value="{{ $model->name }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>{{trans('backend.time')}}</label><span class="text-danger"> * </span>
                        </div>
                        <div class="col-md-10">
                            <span><input name="start_time" type="text" placeholder="{{trans('laother.choose_start_date')}}" class="datepicker form-control
                            d-inline-block w-25" autocomplete="off" value="{{ get_date($model->start_time) }}"></span>
                            <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                            <span><input name="end_time" type="text" placeholder='{{trans("backend.choose_end_date")}}' class="datepicker form-control
                            d-inline-block w-25" autocomplete="off" value="{{ get_date($model->end_time) }}"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>{{ trans('lacategory.description') }}</label>
                        </div>
                        <div class="col-md-10">
                            <textarea name="description" id="description" class="form-control">{{ $model->description }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>{{ trans('lacategory.status') }}</label>
                        </div>
                        <div class="col-md-10">
                            <input type="radio" name="status" value="1" {{ $model->status == 1 ? 'checked' :  '' }}>&nbsp;&nbsp;{{ trans('lacategory.enable') }}
                            <input type="radio" name="status" value="0" {{ $model->status == 0 ? 'checked' :  '' }} >&nbsp;&nbsp;{{ trans('lacategory.disable') }}
                        </div>
                    </div>
                    @if ($model->id)
                        <div class="row wrapped_armorial_1 mb-2">
                            <div class="col-md-6">
                                <h5>{{ trans('latraining.fastest_learning_badge') }}</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" onclick="addArmorial(1)" class="btn">
                                    <i class="fa fa-plus"></i> {{ trans('lacategory.add_child_badge') }}
                                </button>
                            </div>
                            <div class="col-md-12 mt-1">
                                <table class="tDefault table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ trans('lacategory.image') }}</th>
                                            <th class="text-center">{{ trans('lacategory.rank') }}</th>
                                            <th class="text-center">{{ trans('lacategory.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody_armorial_1">
                                        @foreach ($armorials_1 as $key1 => $armorial_1)
                                            <tr class="tr_{{ $armorial_1->id }}">
                                                <input type="hidden" class="armorial_image_{{ $armorial_1->id }}" value="{{ image_file($armorial_1->image) }}">
                                                <input type="hidden" class="armorial_level_{{ $armorial_1->id }}" value="{{ $armorial_1->level }}">
                                                <th>{{ $key1 + 1 }}</th>
                                                <th><img src="{{ image_file($armorial_1->image) }}" alt="" class="img-reponsive image_{{ $armorial_1->id }}" width="130px"></th>
                                                <th class="text-center"><span class="level_{{ $armorial_1->id }}">{{ $armorial_1->level }}</span></th>
                                                <th class="text-center">
                                                    <a onclick="editArmorial(1, {{ $armorial_1->id }})" class="text-primary cursor_pointer edit_armorial_{{ $armorial_1->id }}">
                                                        <i class="fa fa-1x fa-edit"></i>
                                                    </a> 
                                                    <a onclick="removeArmorial(1, {{ $armorial_1->id }})" class="text-danger cursor_pointer remove_armorial_{{ $armorial_1->id }}">
                                                        <i class="fa fa-1x fa-trash"></i>
                                                    </a>
                                                </th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row wrapped_armorial_2 mb-2">
                            <div class="col-md-6">
                                <h5>{{ trans('latraining.top_score_badge') }}</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" onclick="addArmorial(2)" class="btn">
                                    <i class="fa fa-plus"></i> {{ trans('lacategory.add_child_badge') }}
                                </button>
                            </div>
                            <div class="col-md-12 mt-1">
                                <table class="tDefault table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ trans('lacategory.image') }}</th>
                                            <th class="text-center">{{ trans('lacategory.rank') }}</th>
                                            <th class="text-center">{{ trans('lacategory.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody_armorial_2">
                                        @foreach ($armorials_2 as $key2 => $armorial_2)
                                            <input type="hidden" class="armorial_image_{{ $armorial_2->id }}" value="{{ image_file($armorial_2->image) }}">
                                            <input type="hidden" class="armorial_level_{{ $armorial_2->id }}" value="{{ $armorial_2->level }}">
                                            <tr class="tr_{{ $armorial_2->id }}">
                                                <th>{{ $key2 + 1 }}</th>
                                                <th><img src="{{ image_file($armorial_2->image) }}" alt="" class="img-reponsive image_{{ $armorial_2->id }}" width="130px"></th>
                                                <th class="text-center"><span class="level_{{ $armorial_2->id }}">{{ $armorial_2->level }}</span></th>
                                                <th class="text-center">
                                                    <a onclick="editArmorial(2, {{ $armorial_2->id }})" class="text-primary cursor_pointer edit_armorial_{{ $armorial_1->id }}">
                                                        <i class="fa fa-1x fa-edit"></i>
                                                    </a> 
                                                    <a onclick="removeArmorial(2, {{ $armorial_2->id }})" class="text-danger cursor_pointer remove_armorial_{{ $armorial_2->id }}">
                                                        <i class="fa fa-1x fa-trash"></i>
                                                    </a>
                                                </th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row wrapped_armorial_3 mb-2">
                            <div class="col-md-6">
                                <h5>{{ trans('latraining.earliest_interactive_badge') }}</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" onclick="addArmorial(3)" class="btn">
                                    <i class="fa fa-plus"></i> {{ trans('lacategory.add_child_badge') }}
                                </button>
                            </div>
                            <div class="col-md-12 mt-1">
                                <table class="tDefault table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ trans('lacategory.image') }}</th>
                                            <th class="text-center">{{ trans('lacategory.rank') }}</th>
                                            <th class="text-center">{{ trans('lacategory.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody_armorial_3">
                                        @foreach ($armorials_3 as $key3 => $armorial_3)
                                            <input type="hidden" class="armorial_image_{{ $armorial_3->id }}" value="{{ image_file($armorial_3->image) }}">
                                            <input type="hidden" class="armorial_level_{{ $armorial_3->id }}" value="{{ $armorial_3->level }}">
                                            <tr class="tr_{{ $armorial_3->id }}">
                                                <th>{{ $key3 + 1 }}</th>
                                                <th>
                                                    <img src="{{ image_file($armorial_3->image) }}" alt="" class="img-reponsive image_{{ $armorial_3->id }}" width="130px">
                                                </th>
                                                <th class="text-center"><span class="level_{{ $armorial_3->id }}">{{ $armorial_3->level }}</span></th>
                                                <th class="text-center">
                                                    <a onclick="editArmorial(3, {{ $armorial_3->id }})" class="text-primary cursor_pointer edit_armorial_{{ $armorial_1->id }}">
                                                        <i class="fa fa-1x fa-edit"></i>
                                                    </a> 
                                                    <a onclick="removeArmorial(3, {{ $armorial_3->id }})" class="text-danger cursor_pointer remove_armorial_{{ $armorial_3->id }}">
                                                        <i class="fa fa-1x fa-trash"></i>
                                                    </a>
                                                </th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row wrapped_course_online mb-2">
                            <div class="col-md-6">
                                <h5>{{ trans('lamenu.online_course') }}</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" onclick="addCourse(1)" class="btn">
                                    <i class="fa fa-plus"></i> {{ trans('labutton.add_new_online') }}
                                </button>
                            </div>
                            <div class="col-md-12 mt-1">
                                <table class="tDefault table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="w-20">{{ trans('latraining.course_code') }}</th>
                                            <th class="w-50">{{ trans("latraining.course_name") }}</th>
                                            <th class="w-20">{{ trans('latraining.time') }}</th>
                                            <th class="text-center w-5">{{ trans('lacategory.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody_course_1">
                                        @foreach ($courseOnlines as $keyOnline => $courseOnline)
                                            <tr class="tr_course_{{ $courseOnline->id }}">
                                                <th>{{ $keyOnline + 1 }}</th>
                                                <th><span>{{ $courseOnline->course_code }}</span></th>
                                                <th><span>{{ $courseOnline->course_name }}</span></th>
                                                <th>{{ get_date($courseOnline->start_date) }} => {{ get_date($courseOnline->end_date) }}</th>
                                                <th class="text-center">
                                                    <a onclick="removeCourse(1, {{ $courseOnline->id }})" class="text-danger cursor_pointer remove_course_{{ $courseOnline->id }}">
                                                        <i class="fa fa-1x fa-trash"></i>
                                                    </a>
                                                </th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
                <input type="hidden" name="id" value="{{ $model->id }}">
            </div>
        </div>
    </form>

    <div class="modal fade" id="modal-child" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-success="add_child_success" data-backdrop="static" data-keyboard="false">
        <form action="{{ route('module.usermedal.save') }}" method="post" class="form-ajax" enctype="multipart/form-data">
            <input type="hidden" name="armorial_type" class="armorial_type" value="">
            <input type="hidden" name="armorial_id" class="armorial_id" value="">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('lacategory.rank') }}</label>
                            </div>
                            <div class="col-md-9">
                                <input type="number" min="1" max="20" class="form-control" name="level" value="" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('lacategory.image') }} <span class="text-danger">*</span> (150x150) </label>
                            </div>
                            <div class="col-md-9">
                                <a href="javascript:void(0)" id="select-image-child">{{trans('lacategory.choose_picture')}}</a>
                                <div id="image-review-child"></div>
                                <input type="hidden" id="image-select-child" name="photo" value="">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        @can(['emulation-badge-create', 'emulation-badge-edit'])
                            <button type="button" onclick="saveArmorial()" class="btn save_armorial">
                                <i class="fa fa-save"></i> {{ trans('labutton.save') }}
                            </button>
                        @endcan
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="modal-course-online" tabindex="-1" role="dialog" aria-labelledby="modalLabelCourse" aria-hidden="true" data-success="add_child_success" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabelCourse">{{ trans('lamenu.online_course') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('ladashboard.course') }}</label>
                        </div>
                        <div class="col-md-9">
                            <select id="course_online_select" class="select2" data-placeholder="{{ trans('ladashboard.course') }}">
                                <option value=""></option>
                                @foreach ($allCourseOnline as $courseOnline)
                                    <option value="{{ $courseOnline->course_id }}">{{ $courseOnline->code }} - {{ $courseOnline->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    @can(['emulation-badge-create', 'emulation-badge-edit'])
                        <button type="button" onclick="saveCourse(1)" class="btn save_course">
                            <i class="fa fa-save"></i> {{ trans('labutton.save') }}
                        </button>
                    @endcan
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        CKEDITOR.replace('description', {
            filebrowserImageBrowseUrl: '/filemanager?type=image',
            filebrowserBrowseUrl: '/filemanager?type=file',
            filebrowserUploadUrl : null, //disable upload tab
            filebrowserImageUploadUrl : null, //disable upload tab
            filebrowserFlashUploadUrl : null, //disable upload tab
        });

        $("#select-image-child").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-review-child").html('<img style="height: 100px; width: auto;" src="'+ path +'">');
                $("#image-select-child").val(path);
            });
        });

        // THÊM HUY HIỆU
        function addArmorial(type) {
            $("#image-review-child").html('')
            $("#image-select-child").val('')
            $("input[name=level]").val('')
            $('.armorial_id').val('')
            $('.armorial_type').val(type)
            if(type == 1) {
                $('#exampleModalLabel').html("{{ trans('latraining.fastest_learning_badge') }}")
            } else if (type == 2) {
                $('#exampleModalLabel').html("{{ trans('latraining.top_score_badge') }}")
            } else {
                $('#exampleModalLabel').html("{{ trans('latraining.earliest_interactive_badge') }}")
            }
            $('#modal-child').modal()
        }

        function saveArmorial() {
            var level = $("input[name=level]").val()
            var image = $("#image-select-child").val()
            var id = $('input[name=id]').val()
            let item = $('.save_armorial');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            $('.save_armorial').attr('disabled',true);
            event.preventDefault();
            var type = $('.armorial_type').val()
            var armorialId = $('.armorial_id').val()
            $.ajax({
                url: "{{ route('module.emulation_badge.save_armorial') }}",
                type: 'post',
                data: {
                    type: type,
                    image: image,
                    level: level,
                    id: id,
                    armorialId: armorialId
                }
            }).done(function(data) {
                var html = '';
                item.html(oldtext);
                $('.save_armorial').attr('disabled',false);
                if (data && data.status == 'success') {
                    $('#modal-child').modal('hide');
                    if(armorialId) {
                        $('.armorial_image_'+ data.saveArmorial.id).val(data.image)
                        $('.armorial_level_'+ data.saveArmorial.id).val(data.saveArmorial.level)
                        $('.image_'+ data.saveArmorial.id).attr('src', data.image)
                        $('.level_'+ data.saveArmorial.id).html(data.saveArmorial.level)
                    } else {
                        var rowCount = $('.tbody_armorial_'+ type +' tr').length;
                        html += `<tr class="tr_`+ data.saveArmorial.id +`">
                                    <input type="hidden" class="armorial_image_`+ data.saveArmorial.id +`" value="`+ data.image +`">
                                    <input type="hidden" class="armorial_level_`+ data.saveArmorial.id +`" value="`+ data.saveArmorial.level +`">
                                    <th>`+ (rowCount + 1) +`</th>
                                    <th>
                                        <img src="`+ data.image +`" alt="" class="img-reponsive image_`+ data.saveArmorial.id +`" width="130px">
                                    </th>
                                    <th class="text-center"><span class="level_`+ data.saveArmorial.id +`">`+ data.saveArmorial.level +`</span></th>
                                    <th class="text-center">
                                        <a onclick="editArmorial(`+ type +`, `+ data.saveArmorial.id +`)" class="text-primary cursor_pointer">
                                            <i class="fa fa-1x fa-edit"></i>
                                        </a> 
                                        <a onclick="removeArmorial(`+ type +`, `+ data.saveArmorial.id +`)" class="text-danger cursor_pointer remove_armorial_`+ data.saveArmorial.id +`">
                                            <i class="fa fa-1x fa-trash"></i>
                                        </a>
                                    </th>
                                </tr>`;
                        $('.tbody_armorial_'+ type).append(html);
                    }
                } 
                show_message(data.message, data.status);
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function removeArmorial(type, id) {
            let item = $('.remove_armorial_'+ id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i>');
            $('.remove_armorial_'+ id).attr('disabled',true);
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.emulation_badge.remove_armorial') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                var html = '';
                item.html(oldtext);
                $('.remove_armorial_'+ id).attr('disabled',false);
                if (data && data.status == 'success') {
                    $('.tbody_armorial_'+ type).find('.tr_'+ id).remove();
                } 
                show_message(data.message, data.status);
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function editArmorial(type, id) {
            if(type == 1) {
                $('#exampleModalLabel').html("{{ trans('latraining.fastest_learning_badge') }}")
            } else if (type == 2) {
                $('#exampleModalLabel').html("{{ trans('latraining.top_score_badge') }}")
            } else {
                $('#exampleModalLabel').html("{{ trans('latraining.earliest_interactive_badge') }}")
            }
            $("#image-review-child").html('<img style="height: 100px; width: auto;" src="'+ $('.armorial_image_'+ id).val() +'">')
            $("#image-select-child").val($('.armorial_image_'+ id).val())
            $("input[name=level]").val($('.armorial_level_'+ id).val())
            $('.armorial_type').val(type)
            $('.armorial_id').val(id)
            $('#modal-child').modal()
        }
        //////

        // THÊM KHÓA HỌC
        function addCourse(type) {
            $('#modal-course-online').modal()
        }

        function saveCourse(type) {
            var courseId = $('#course_online_select').val()
            var id = $('input[name=id]').val()
            let item = $('.save_course');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            $('.save_course').attr('disabled',true);
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.emulation_badge.save_course') }}",
                type: 'post',
                data: {
                    type: type,
                    courseId: courseId,
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('.save_course').attr('disabled',false);
                var html = '';
                if (data && data.status == 'success') {
                    $('#modal-course-online').modal('hide');
                    var rowCountCourse = $('.tbody_course_'+ type +' tr').length;
                    html += `<tr class="tr_course_`+ data.saveCourse.id +`">
                                <th>`+ (rowCountCourse + 1) +`</th>
                                <th><span>`+ data.course.code +`</span></th>
                                <th><span>`+ data.course.name +`</span></th>
                                <th>`+ data.course.start_date_format +` => `+ data.course.end_date_format +`</th>
                                <th class="text-center">
                                    <a onclick="removeCourse(2, `+ data.saveCourse.id +`)" class="text-danger cursor_pointer remove_course_`+ data.saveCourse.id +`">
                                        <i class="fa fa-1x fa-trash"></i>
                                    </a>
                                </th>
                            </tr>`;
                    $('.tbody_course_'+ type).append(html);
                } 
                show_message(data.message, data.status);
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function removeCourse(type, id) {
            let item = $('.remove_course_'+ id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i>');
            $('.remove_course_'+ id).attr('disabled',true);
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.emulation_badge.remove_course') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                var html = '';
                item.html(oldtext);
                $('.remove_course_'+ id).attr('disabled',false);
                if (data && data.status == 'success') {
                    $('.tbody_course_'+ type).find('.tr_course_'+ id).remove();
                } 
                show_message(data.message, data.status);
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }
        /////
    </script>
@endsection
