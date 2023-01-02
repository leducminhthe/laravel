@extends('layouts.backend')

@section('page_title', 'Cập nhật source')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.setting') }}">{{ trans('lamenu.setting') }} </a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Cập nhật source</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('backend.save_update_source') }}" method="post" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4 text-right">
                            <div class="btn-group act-btns">
                                <button type="submit" class="btn" data-must-checked="false">
                                    <i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label for="path">{{ trans('latraining.file') }}</label>
                        </div>
                        <div class="col-md-9">
                            <a href="javascript:void(0)" id="select-file">{{ trans('latraining.choose_file') }}</a>
                            <br><em id="path-name"></em>
                            <input type="hidden" name="path" id="path" value="">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $("#select-file").on('click', function () {
            open_filemanager({type: 'scorm'}, function (url, path, name) {
                $("#path-name").html(name);
                $("#path").val(path);
            });
        });
    </script>
@stop
