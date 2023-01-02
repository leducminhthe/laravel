<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table class="tDefault table table-hover bootstrap-table text-nowrap" id="table-modal-document">
                    <thead>
                    <tr>
                        <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="5%">#</th>
                        <th data-field="name">{{ trans('backend.document_name') }}</th>
                        <th data-field="document" data-formatter="document_formatter" data-align="center" data-width="5%">File</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function index_formatter(value, row, index) {
        return (index+1);
    }
    function document_formatter(value, row, index){
        return '<a href="'+row.link_download+'" class="btn"> <i class="fa fa-download"></i> Download</a>';
    }

    var table_modal_document = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.online.detail.document.getdata', [$course_id]) }}',
        table: '#table-modal-document',
    });
</script>
