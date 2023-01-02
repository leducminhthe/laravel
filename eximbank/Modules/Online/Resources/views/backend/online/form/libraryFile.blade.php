<div role="main">
    <div class="container lst row">
        {{--    <h3 class="well">Quản lý file</h3>--}}
            @if(count($errors) > 0)
            <div class="alert alert-danger col-12">
                {{ trans('latraining.error_save_file') }}<br><br>
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            <form method="post" action="{{ route('module.online.uploadfile') }}" enctype="multipart/form-data" class="w-100 mb-2 form-horizontal form-ajax">
                {{ csrf_field() }}
                <a href="javascript:void(0)" style="float: left" class="btn" id="select-document-file">{{ trans('latraining.choose_file') }}</a>
                @if($model->lock_course == 0)
                    <button type="submit" class="btn ml-2">{{ trans('labutton.save') }}</button>
                @endif
                <div>
                    <div id="document-review-file"></div>
                    <input name="filenames" id="document-select-file" type="text" class="d-none" value="">
                    <input type="hidden" name="course_id" value="{{ $model->id }}">
                </div>
            </form>
        </div>

    <div class="row">
        <div class="col-md-8">
            <form class="form-inline form-search mb-3" id="form-search">
                <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('latraining.enter_category') }}">
                <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
            </form>
        </div>
        <div class="col-md-4 text-right act-btns">
            <div class="pull-right">
                @if($model->lock_course == 0)
                <div class="btn-group">
                    <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                </div>
                @endif
            </div>
        </div>
    </div>
    <br>

    <table class="tDefault table table-hover bootstrap-table text-nowrap" id="table-library-file">
        <thead>
            <tr>
                <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="3%">#</th>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="name" data-formatter="name_formatter">{{ trans('latraining.file_list') }}</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function name_formatter(value, row, index) {
        return '<a href="'+ row.uploadFile +'">'+ row.uploadName +'</a>';
    }

    function index_formatter(value, row, index) {
        return (index+1);
    }

    var table_library_file = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.online.get_data_library_file',['course_id' => $model->id]) }}',
        remove_url: '{{ route('module.online.library_file_remove') }}',
        table: '#table-library-file',
    });

    $(document).ready(function () {
        $(".btn-success").click(function () {
            var lsthmtl = $(".clone").html();
            $(".increment").after(lsthmtl);
            // table_library_file.refresh();
        });
        $("body").on("click", ".btn-danger", function () {
            $(this).parents(".hdtuto control-group lst").remove();
        });

        $("#select-document-file").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'file'}, function (url, path) {
                var path2 =  path.split("/");
                $("#document-review-file").html(path2[path2.length - 1]);
                $("#document-select-file").val(path);
            });
        });
    });
</script>
