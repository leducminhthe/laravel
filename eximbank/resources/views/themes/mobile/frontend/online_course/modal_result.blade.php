<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('latraining.result') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="row m-0">
                    <div class="col-12">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 pl-0">
                                        <img class="icon_class" src="{{ asset('images/class.png') }}" alt="">
                                        <span>Lớp học</span>
                                    </div>
                                    <div class="col-7 text-center information_name">
                                        <span>{{ $item->name }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 pl-0">
                                        <img class="icon_class" src="{{ asset('images/last_access.png') }}" alt="">
                                        <span>Ngày tham gia</span>
                                    </div>
                                    <div class="col-7 text-center information_access">
                                        <span>{{ !empty($date_join) ? date("d-m-Y H:i", strtotime($date_join->created_at)) : '' }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 pl-0">
                                        <img class="icon_class" src="{{ asset('images/score.png') }}" alt="">
                                        <span>Điểm tổng kết</span>
                                    </div>
                                    <div class="col-7 text-center information_score">
                                        <span>{{ !empty($get_result) ? $get_result->score : '0' }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 pl-0">
                                        <img class="icon_class" src="{{ asset('images/result.png') }}" alt="">
                                        <span>{{ trans('latraining.result') }}</span>
                                    </div>
                                    <div class="col-7 text-center information_result">
                                        <span>{{ !empty($get_result) && $get_result->result == 1 ? 'Hoàn thành' : 'Chưa hoàn thành' }}</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 mt-2">
                        <table class="table table-striped">
                            <tbody>
                            @foreach ($get_activity_courses as $get_activity_course)
                                @php
                                    $count_total_view = \Modules\Online\Entities\OnlineCourseActivityHistory::where('course_id',$get_activity_course->course_id)->where('course_activity_id',$get_activity_course->id)->count();
                                    $get_activity_completion = Modules\Online\Entities\OnlineCourseActivityCompletion::where('course_id',$get_activity_course->course_id)->where('activity_id',$get_activity_course->id)->first();
                                @endphp
                                <tr>
                                    <th>
                                        {{ trans("latraining.content") }}: {{ $get_activity_course->name }}
                                        <br>
                                        Điều kiện hoàn thành: {{ in_array($get_activity_course->id, $condition_activity) ? trans('latraining.yes') : trans('latraining.no') }}
                                        <br>
                                        {{ trans('latraining.result') }}: {{ $get_activity_completion && $get_activity_completion->status == 1 ? 'Hoàn thành' : 'Chưa hoàn thành' }}
                                    </th>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

</script>
