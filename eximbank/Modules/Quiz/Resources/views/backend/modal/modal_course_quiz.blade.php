<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title font-weight-bold">Thông tin khóa học</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @if ($course_view)
                    <div class="row p-2">
                        <div class="col-2">{{ trans('lamenu.course') }}</div>
                        <div class="col-10">{{ $course_view->name .' ('. $course_view->code .')' }}</div>
                    </div>
                    <div class="row p-2">
                        <div class="col-2">{{ trans('latraining.time') }}</div>
                        <div class="col-10">
                            {{ get_date($course_view->start_date) }} 
                            @if ($course_view->end_date)
                                <i class="fa fa-arrow-right"></i> {{ get_date($course_view->end_date) }}
                            @endif
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-2">{{ trans('lacategory.form') }}</div>
                        <div class="col-10">{{ $course_type_text }}</div>
                    </div>
                    @if ($course_url)
                    <div class="row p-2">
                        <div class="col-2">Link khóa học</div>
                        <div class="col-10">
                            <a href="{{ $course_url }}" target="_blank" class="btn"> Chi tiết</a>
                        </div>
                    </div>
                    @endif
                @else
                    <span>{{ trans('laother.not_found') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

