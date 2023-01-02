<div>
    <h2 class="float-left">{{ trans('latraining.get_point_completion_exam') }}</h2>
    <span class="float-right">
        @if (!isset($result))
            <button type="button" class="btn load-modal" data-url="{{ route('module.quiz.userpoint-setting-quiz', [$model->id]) }}">
                <i class="fa fa-plus" aria-hidden="true"></i> {{ trans('latraining.add_criteria') }}
            </button>
        @endif
    </span>
</div>
<table class="table table-bordered table_setting_complete">
    <thead>
        <tr class="bg-info">
            <th scope="col" class="text-center">#</th>
            <th scope="col" class="text-center">{{ trans('latraining.codition') }}</th>
            {{-- <th scope="col" class="text-center">{{ trans('latraining.score') }}</th> --}}
            <th scope="col" class="text-center">{{ trans('latraining.date_update') }}</th>
            <th scope="col" class="text-center">{{ trans('latraining.manipulation') }}</th>
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
                    @elseif ($v->note == 'attempt')
                        Từ lần {{ intval($v->min_score) }} đến lần {{ intval($v->max_score) }}
                    @elseif ($v->note == 'timefinish')
                        Hoàn thành kỳ thi sớm nhất
                    @else
                        {{ trans('latraining.completing_exam') }}
                    @endif
                </td>
                {{-- <td class="text-center">{{ $v->pvalue }}</td> --}}
                <td class="text-center">{{ get_date($v->updated_at, 'H:i:s d/m/Y') }}</td>
                <td class="text-center">
                    <i data-url="{{ route('module.quiz.edit-userpoint-setting-quiz', [$model->id, $v->id]) }}" data-item="{{ $v->id }}" data-quiz="{{ $model->id }}" style="cursor: pointer;" class="far fa-edit load-modal"></i>
                    @if (!isset($result))
                        <i data-item="{{ $v->id }}" data-quiz="{{ $model->id }}" style="cursor: pointer;" class="fas fa-trash-alt remove-setting-item"></i>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
