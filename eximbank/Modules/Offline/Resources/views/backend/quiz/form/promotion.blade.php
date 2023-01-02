<div>
    <h2 class="float-left">Nhận điểm khi hoàn thành kỳ thi</h2>
    <span class="float-right">
        <button type="button" class="btn load-modal" data-url="{{ route('module.offline.quiz.userpoint-setting-quiz', [$course_id, $model->id]) }}">
            <i class="fa fa-plus" aria-hidden="true"></i> Thêm tiêu chí
        </button>
    </span>
</div>
<table class="table table-bordered table_setting_complete">
    <thead>
        <tr class="bg-info">
            <th scope="col" class="text-center">#</th>
            <th scope="col" class="text-center">Điều kiện</th>
            <th scope="col" class="text-center">Điểm số</th>
            <th scope="col" class="text-center">Ngày cập nhật</th>
            <th scope="col" class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach($userpoint as $v)
            <tr>
                <th scope="row" class="text-center">{{ $loop->index + 1 }}</th>
                <td class="text-center">
                    @if ($v->note == 'timecompleted')
                        {{ date('H:i d/m/Y', $v->start_date) }} <i class="fas fa-long-arrow-alt-right"></i> {{ date('H:i d/m/Y', $v->end_date) }}
                    @elseif ($v->note == 'score')
                        Từ điểm {{ $v->min_score }} đến điểm {{ $v->max_score }}
                    @else
                        Từ lần {{ intval($v->min_score) }} đến lần {{ intval($v->max_score) }}
                    @endif
                </td>
                <td class="text-center">{{ $v->pvalue }}</td>
                <td class="text-center">{{ get_date($v->updated_at, 'H:i:s d/m/Y') }}</td>
                <td class="text-center">
                    <i data-url="{{ route('module.offline.quiz.edit-userpoint-setting-quiz', [$course_id, $model->id, $v->id]) }}" data-item="{{ $v->id }}" data-quiz="{{ $model->id }}" style="cursor: pointer;" class="far fa-edit load-modal"></i>
                    <i data-item="{{ $v->id }}" data-quiz="{{ $model->id }}" style="cursor: pointer;" class="fas fa-trash-alt remove-setting-item"></i>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    $('.table_setting_complete').on('click', '.remove-setting-item', function () {
        let item = $(this);
        let id = item.data('item');
        let quiz_id = item.data('quiz');

        if (!id) {
            return false;
        }

        if (!confirm('Bạn có chắc chắn muốn xóa mục này?')) {
            return false;
        }

        $.ajax({
            type: "POST",
            url: "/admin-cp/offline/{{ $course_id }}/quiz/userpoint-setting/"+ quiz_id +"/delete/"+id,
            dataType: 'json',
            data: {
                'id': id,
            },
            success: function (result) {
                if (result.status) {
                    item.closest('tr').remove();
                }
            }
        });
    });
</script>