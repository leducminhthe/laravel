<div class="">
    <form method="post" action="{{ route('module.offline.upload_document', [$model->id]) }}" enctype="multipart/form-data" class="form-horizontal form-ajax">
        {{ csrf_field() }}
        <input type="hidden" name="id" id="id" class="form-control" value="">

        <div class="form-group row">
            <div class="col-3">
                <label for="name">{{ trans('backend.document_name') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-9">
                <input type="text" name="name" id="name" value="" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-3">
                <label for="document">{{ trans('backend.attach_file') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-9">
                <a href="javascript:void(0)" class="" id="select-file-manager">{{ trans('latraining.choose_file') }}</a>
                <div id="file-manager-review">

                </div>
                <input name="document" id="file-manager-select" type="text" class="d-none" value="">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-3"></div>
            <div class="col-9">
                @if($model->lock_course == 0)
                    <button type="submit" class="btn ml-2" id="btn-save"> {{ trans('labutton.save') }}</button>
                @endif
            </div>
        </div>
    </form>
    <div role="main" class="mt-2">
        <div class="row">
            <div class="col-md-8">
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
        <table class="tDefault table table-hover bootstrap-table text-nowrap" id="table-document">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="3%">#</th>
                    <th data-field="name">{{ trans('backend.document_name') }}</th>
                    <th data-field="document_name">File</th>
                    <th data-field="action" data-formatter="action_formatter" data-align="center" data-width="5%">{{ trans('latraining.action') }}</th>
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

    function index_formatter(value, row, index) {
        return (index+1);
    }
    function action_formatter(value, row, index){
        return '<a class="update-document cursor_pointer" title="Chỉnh sửa" data-id="'+row.id+'" data-name="'+row.name+'" data-document="'+row.document+'" data-document_name="'+row.document_name+'"> <i class="fa fa-edit"></i></a>';
    }

    var table_document = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.offline.get_data_document',['course_id' => $model->id]) }}',
        remove_url: '{{ route('module.offline.remove_document') }}',
        table: '#table-document',
    });

    $('#table-document').on('click', '.update-document', function(){
        var id = $(this).data('id');
        var name = $(this).data('name');
        var document = $(this).data('document');
        var document_name = $(this).data('document_name');

        $('#id').val(id);
        $('#name').val(name);
        $('#file-manager-select').val(document);
        $('#file-manager-review').html(document_name);
    });
</script>
