@extends('layouts.backend')

@section('page_title', trans('lamenu.learning_path'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.learning_manager'),
                'url' => route('module.training_by_title')
            ],
            [
                'name' => trans('lamenu.learning_path'). ': '. $training_titles->name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <button type="button" class="btn" data-toggle="modal" data-target="#add-training-title-category"><i class="fa fa-plus-circle">
                        </i> {{ trans('laother.add_category') }}
                    </button>
                </div>
            </div>
        </div>
        <br>
        <div id="list-category">
            <div class="row">
                @if (!empty($training_titles_categories))
                    @foreach($training_titles_categories as $training_titles_category)
                        @php
                            $training_titles_detail = $training_titles_category->trainingtitledetail;
                        @endphp
                        <div class="col-3 mb-3 pr-1">
                            <div class="card">
                                <div class="card-header p-2 pr-0">
                                <span id="name_training_title_category_{{ $training_titles_category->id }}">
                                    {{ $training_titles_category->name }}
                                </span>
                                <br>
                                <span>{{ trans('laother.time_complete') }}: {{ $training_titles_category->num_date_category }} {{ trans('latraining.date') }}</span>

                                    <div class="eps_dots more_dropdown">
                                        <a href="javascript:void(0)"><i class='uil uil-ellipsis-v'></i></a>
                                        <div class="dropdown-content">
                                        <span href="javascript:void(0)" style="cursor: pointer" class="ml-1 edit-category-item" data-id="{{ $training_titles_category->id }}" data-num_date_category="{{ $training_titles_category->num_date_category }}">
                                            <i class="fas fa-edit"></i> {{ trans('latraining.edit') }}
                                        </span>
                                        <span href="javascript:void(0)" style="cursor: pointer" class="ml-1 remove-category-item" data-id="{{ $training_titles_category->id }}">
                                            <i class="fas fa-trash"></i> {{ trans('labutton.delete') }}
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-group list-group-flush">
                                    @foreach($training_titles_detail as $detail)
                                    <li class="list-group-item p-2">
                                        {{ $detail->subject_name }} <br>
                                        <p class="my-1">{{ trans('lareport.duration') }}: {{ $detail->num_time }}
                                            <span href="javascript:void(0)" style="cursor: pointer; float: right;" class="remove-detail-item text-danger" data-id="{{ $detail->id }}"><i class="fas fa-trash"></i></span>
                                            <span href="javascript:void(0)" style="cursor: pointer; float: right;" class="edit-detail-item text-danger mr-1" data-id="{{ $detail->id }}" data-training_title_category_id="{{ $detail->training_title_category_id }}" data-num_time="{{ $detail->num_time }}"><i class="fas fa-edit"></i></span>
                                        </p>
                                    </li>
                                    @endforeach
                                </ul>
                                <div class="card-footer text-center">
                                    <button type="button" class="btn add-training-title-detail" data-category_id="{{ $training_titles_category->id }}"><i class="fa fa-plus-circle">
                                        </i> {{ trans('laother.add_subject') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="modal fade" id="add-training-title-category" role="dialog" aria-labelledby="modal-title2" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <form action="{{ route('module.training_by_title.detail.save_category', ['id' => $training_titles->id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="id" class="training_title_category_id" value="">
                <div class="modal-dialog" role="document">
                    <div class="modal-content 1">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-title2">{{ trans('laother.add_category') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>{{ trans('lamenu.category') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control training_title_category_name" required>
                            </div>

                            <div class="form-group">
                                <label>{{ trans('laother.time_complete') }} ({{ trans('latraining.date') }}) <span class="text-danger">*</span></label>
                                <input type="number" name="num_date_category" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn"><i class="fa fa-save"></i> @lang('labutton.save')</button>
                            <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times-circle"></i> @lang('labutton.close')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal fade" id="add-training-title-detail" role="dialog" aria-labelledby="modal-title2" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <form action="{{ route('module.training_by_title.detail.save', ['id' => $training_titles->id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="id" value="">
                <input type="hidden" name="training_title_category_id" value="">
                <div class="modal-dialog" role="document">
                    <div class="modal-content 1">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-title2">{{ trans('laother.add_subject') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group subject_training_title_detail">
                                <label>{{ trans('laprofile.subject') }} <span class="text-danger">*</span></label>
                                <select name="subject_id" id="subject_id" class="form-control load-subject" data-placeholder="{{ trans('ladashboard.subject') }}" required></select>
                            </div>

                            {{-- <div class="form-group">
                                <label>Thời gian cần hoàn thành (Tính từ ngày vào làm) <span class="text-danger">*</span></label>
                                <input type="text" name="num_date" class="form-control">
                            </div> --}}

                            <div class="form-group">
                                <label>{{ trans('lareport.duration') }} ({{ trans('latraining.session') }}) <span class="text-danger">*</span></label>
                                <input type="text" name="num_time" placeholder="{{ trans('laother.enter_duration') }}" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn"><i class="fa fa-save"></i> @lang('labutton.save')</button>
                            <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times-circle"></i> @lang('labutton.close')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal fade" id="edit-training-title-detail" role="dialog" aria-labelledby="modal-title2" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <form action="{{ route('module.training_by_title.detail.edit_detail', ['id' => $training_titles->id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="id_training_detail" value="">
                <input type="hidden" name="training_title_category_id_edit" value="">
                <div class="modal-dialog" role="document">
                    <div class="modal-content 1">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-title2">{{ trans('latraining.edit') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{-- <div class="form-group">
                                <label>Thời gian cần hoàn thành <span class="text-danger">*</span></label>
                                <input type="text" name="edit_num_date" class="form-control">
                            </div> --}}

                            <div class="form-group">
                                <label>{{ trans('lareport.duration') }} ({{ trans('latraining.session') }}) <span class="text-danger">*</span></label>
                                <input type="text" name="edit_num_time" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn"><i class="fa fa-save"></i> @lang('labutton.save')</button>
                            <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times-circle"></i> @lang('labutton.close')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <script type="text/javascript">
            $('#list-category').on('click', '.add-training-title-detail', function () {
                var category_id = $(this).data('category_id');

                $('#add-training-title-detail input[name=training_title_category_id]').val(category_id);

                $('#add-training-title-detail').modal();
            });

            $('#list-category').on('click', '.remove-detail-item', function () {
                var btn = $(this);
                var id = $(this).data('id');

                $.ajax({
                    url: '{{ route('module.training_by_title.detail.remove', ['id' => $training_titles->id]) }}',
                    type: 'post',
                    data:{
                        id: id
                    },
                }).done(function(data) {
                    btn.parents('.list-group-item').remove();
                }).fail(function (data) {
                    show_message('{{ trans('laother.data_error') }}', 'error');
                    return false;
                });
            });

            $('#list-category').on('click', '.remove-category-item', function () {
                var btn = $(this);
                var id = $(this).data('id');

                $.ajax({
                    url: '{{ route('module.training_by_title.detail.remove_category', ['id' => $training_titles->id]) }}',
                    type: 'post',
                    data:{
                        id: id
                    },
                }).done(function(data) {
                    btn.parents('.col-3').remove();
                }).fail(function (data) {
                    show_message('{{ trans('laother.data_error') }}', 'error');
                    return false;
                });
            });

            $('#list-category').on('click', '.edit-category-item', function () {
                var btn = $(this);
                var id = $(this).data('id');
                var num_date_category = $(this).data('num_date_category');
                var training_title_category = document.getElementById("name_training_title_category_"+id).innerText;
                $("input[name='num_date_category']").val(num_date_category);
                $('#modal-title2').html('Sửa danh mục')
                $('.training_title_category_name').val(training_title_category)
                $('.training_title_category_id').val(id);
                $('#add-training-title-category').modal();
            });

            $('#list-category').on('click', '.edit-detail-item', function () {
                var btn = $(this);
                var id = $(this).data('id');
                var training_title_category_id = $(this).data('training_title_category_id');
                var num_time = $(this).data('num_time');
                $("input[name='id_training_detail']").val(id);
                $("input[name='training_title_category_id_edit']").val(training_title_category_id);
                $("input[name='edit_num_time']").val(num_time);
                $('#edit-training-title-detail').modal();
            });
        </script>
    </div>
@endsection
