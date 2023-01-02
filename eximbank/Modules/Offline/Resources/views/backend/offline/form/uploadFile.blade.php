<div class="container lst">
    @if(count($errors) > 0)
        <div class="alert alert-danger">
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
    
    <form method="post" action="{{ route('module.offline.uploadfile') }}" enctype="multipart/form-data" class="form-horizontal form-ajax">
        {{ csrf_field() }}
        <div class="d_flex_align">
            <a href="javascript:void(0)" class="btn" id="select-file-manager">{{ trans('latraining.choose_file') }}</a>
            @if($model->lock_course == 0)
                <button type="submit" class="btn ml-2">{{ trans('labutton.save') }}</button>
            @endif
        </div>
        <div>
            <div id="file-manager-review"></div>
            <input name="filenames" id="file-manager-select" type="text" class="d-none" value="">
            <input type="hidden" name="course_id" value="{{ $model->id }}">
        </div>
    </form>

    <div role="main" class="mt-2">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('latraining.enter_category') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @if($model->lock_course == 0)
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>    
        <table class="tDefault table table-hover bootstrap-table text-nowrap" id="table-library-file">
            <thead>
                <tr>
                    <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="3%">#</th>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('latraining.list_file') }}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".btn-success").click(function () {
            var lsthmtl = $(".clone").html();
            $(".increment").after(lsthmtl);
        });
        $("body").on("click", ".btn-danger", function () {
            $(this).parents(".hdtuto control-group lst").remove();
        });

        $("#select-file-manager").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'file'}, function (url, path) {
                var path2 =  path.split("/");
                $("#file-manager-review").html(path2[path2.length - 1]);
                $("#file-manager-select").val(path);
            });
        });
    });

    function name_formatter(value, row, index) {
        return '<a href="'+ row.uploadFile +'">'+ row.uploadName +'</a>';
    }

    function index_formatter(value, row, index) {
        return (index+1);
    }

    var table_library_file = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.offline.get_data_library_file',['course_id' => $model->id]) }}',
        remove_url: '{{ route('module.offline.library_file_remove') }}',
        table: '#table-library-file',
    });
</script>
