@extends('themes.mobile.layouts.app')

@section('page_title', data_locale('Chứng chỉ bên ngoài', 'My certificate'))

@section('content')
    <style>
        textarea {
            resize: none;
        }
        #count_message {
            background-color: #dee2e6;
            margin-top: -25px;
            margin-right: 5px;
            border-radius: 5px;
        }
        #add_certifivate {
            border-radius: 20px;
        }
    </style>
    <div class="container mt-2">
        <form method="POST" action="{{ route('themes.mobile.front.my_certificate.save') }}" class="form-validate form-ajax bg-white p-2" role="form" enctype="multipart/form-data" id="add_my_certificate">
        <div class="row mt-2">
            <div class="col-12 mb-2">
                <p class="mb-1">
                    {{ trans('laprofile.certificate_name') }}
                    <span class="text-danger">*</span>
                </p>
                <input type="text" name="name_certificate" class="form-control" placeholder="Nhập tên chứng chỉ" value="{{ $certificate->name_certificate }}" required>
            </div>
            <div class="col-12 mb-2">
                <p class="mb-1">
                    {{ trans('laprofile.certificate_school') }}
                    <span class="text-danger">*</span>
                </p>
                <input type="text" name="name_school" class="form-control" placeholder="Nhập tên trường" value="{{ $certificate->name_school }}" required>
            </div>
            <div class="col-12 mb-2">
                <p class="mb-1">
                    {{ trans('laprofile.study_time') }}
                    <span class="text-danger">*</span>
                </p>
                <input name="time_start"
                    type="text" class="datepicker form-control"
                    placeholder="{{trans('laother.choose_start_date')}}"
                    autocomplete="off" value="{{ get_date($certificate->time_start) }}"
                    required
                >
            </div>
            <div class="col-12 mb-2">
                <p class="mb-1">
                    {{ trans('laprofile.date_issue') }}
                    <span class="text-danger">*</span>
                </p>
                <input name="date_license"
                    type="text" class="datepicker form-control"
                    placeholder="{{ trans('laother.choose_end_date') }}"
                    autocomplete="off" value="{{ get_date($certificate->date_license) }}"
                    required
                >
            </div>
            <div class="col-12 mb-2">
                <p class="mb-1">
                    {{ trans('latraining.score') }}
                    <span class="text-danger">*</span>
                </p>
                <input type="number" name="score" class="form-control" placeholder="Nhập điểm" value="{{ $certificate->score }}" required>
            </div>
            <div class="col-12 mb-2">
                <p class="mb-1">{{ trans('latraining.result') }} <span class="text-danger">*</span></p>
                <input type="text" name="result" class="form-control" placeholder="Nhập kết quả" value="{{ $certificate->result }}" required>
            </div>
            <div class="col-12 mb-2">
                <p class="mb-1">{{ trans('laprofile.rank') }}</p>
                <input type="text" name="rank" class="form-control" placeholder="Nhập cấp bậc" value="{{ $certificate->rank }}">
            </div>
            <div class="col-12 mb-2">
                <p class="mb-1">{{ trans('laprofile.certificate_image') }} <span class="text-danger">*</span></p>
                <input type="file" id="file" placeholder="{{ trans('laprofile.select_certificate') }}" name="certificate" accept="image/png, image/gif, image/jpeg">
                @if ($certificate->certificate)
                    <input type="hidden" name="path_old" id="path_old" value="{{ $certificate->certificate }}">
                    <div class="name_path_old">
                        <span>{{ basename($certificate->certificate) }}</span>
                    </div>
                @endif
            </div>
            <div class="col-12 mb-2">
                <p class="mb-1">{{ trans('latraining.note') }}</p>
                <textarea class="form-control" id="note" name="note" maxlength="200" placeholder="{{ trans('latraining.note') }}" rows="5">{{ $certificate->note }}</textarea>
                <span class="float-right px-1" id="count_message"></span>
            </div>
            <div class="col-12 mt-2">
                <button id="add_certifivate" type="submit" class="btn w-100" data-must-checked="false">
                    <i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}
                </button>
            </div>
        </div>
        </form>
    </div>
@endsection

@section('footer')
    <script>
        var fileUpload = document.getElementById("file");
        var addAcitivty = document.getElementById("add_certifivate");
        addAcitivty.addEventListener("click", function (event) {
            if (fileUpload.files.length != 0) {
                $('#path_old').val('');
                return;
            }
        })

        var text_max = 200;
        $('#count_message').html('0 / ' + text_max );

        $('#note').keyup(function() {
            var text_length = $('#note').val().length;
            var text_remaining = text_max - text_length;
            $('#count_message').html(text_length + ' / ' + text_max);
        }); 
    </script>
@endsection

