<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @foreach ($teacher_graded as $key => $teacher)
                    <div class="row mt-2">
                        <div class="col-12">
                            {{ ($key + 1) .'. '. $teacher->full_name .' ('. $teacher->code .')' }} <br>
                            Ngày chấm: {{ get_date($teacher->created_at, 'H:i:s d/m/Y') }}
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>

