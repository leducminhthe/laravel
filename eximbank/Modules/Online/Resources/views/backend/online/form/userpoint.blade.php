@php
    $tab = Request::segment(3);
    $type = $tab == 'course-for-offline' ? 1 : 0;
@endphp
{{-- Nhận điểm khi hoàn thành khóa học --}}
<div>
    <h2 class="float-left">{{ trans('latraining.get_point_completion_course') }}</h2>
    <span class="float-right">
        @if ($model->lock_course == 0)
            <button type="button" class="btn load-modal" data-url="{{ route('module.online.userpoint-setting-complete', ['course_id' => $model->id, 'type' => $type]) }}">
                <i class="fa fa-plus" aria-hidden="true"></i> {{ trans('latraining.add_criteria') }}
            </button>
        @endif
    </span>
</div>
<table class="table table-bordered table_setting_complete">
    <thead>
        <tr class="bg-info">
            <th scope="col" class="text-center">#</th>
            <th scope="col" class="text-center">{{ trans('latraining.time_complete') }}</th>
            <th scope="col" class="text-center">{{ trans('latraining.score') }}</th>
            <th scope="col" class="text-center">{{ trans('latraining.date_update') }}</th>
            <th scope="col" class="text-center">{{ trans('latraining.manipulation') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($setting_complete as $v)
            <tr>
                <th scope="row" class="text-center">{{ $loop->index + 1 }}</th>
                <td class="text-center">
                    {{ date('H:i d/m/Y', $v->start_date) }} 
                    @if ($v->end_date)
                        <i class="fas fa-long-arrow-alt-right"></i> {{ date('H:i d/m/Y', $v->end_date) }}
                    @endif
                </td>
                <td class="text-center">{{ $v->pvalue }}</td>
                <td class="text-center">{{ get_date($v->updated_at, 'H:i:s d/m/Y') }}</td>
                <td class="text-center">
                    @if ($model->lock_course == 0)
                        <i data-url="{{ route('module.online.edit-userpoint-setting-complete', [$model->id, $type, $v->id]) }}" data-item="{{ $v->id }}" data-course="{{ $model->id }}" style="cursor: pointer;" class="far fa-edit load-modal"></i> 
                        <i data-item="{{ $v->id }}" data-course="{{ $model->id }}" style="cursor: pointer;" class="fas fa-trash-alt remove-setting-item"></i>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Nhận điểm khi hoàn thành hoạt động --}}
<div>
    <h2 class="float-left">{{ trans('latraining.get_point_completion_activity') }}</h2>
    <span class="float-right">
        @if ($model->lock_course == 0)
            <button type="button" class="btn load-modal"  data-url="{{ route('module.online.userpoint-setting-module', ['course_id' => $model->id, 'type' => $type]) }}">
                <i class="fa fa-plus" aria-hidden="true"></i> {{ trans('latraining.add_criteria') }}
            </button>
        @endif
    </span>
</div>
<table class="table table-bordered table_setting_complete">
    <thead>
        <tr class="bg-info">
            <th scope="col" class="text-center">#</th>
            <th scope="col" class="text-center">{{ trans('latraining.activity') }}</th>
            <th scope="col" class="text-center">Điều kiện hoàn thành</th>
            <th scope="col" class="text-center">{{ trans('latraining.score') }}</th>
            <th scope="col" class="text-center">{{ trans('latraining.date_update') }}</th>
            <th scope="col" class="text-center">{{ trans('latraining.manipulation') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($setting_modules as $v)
            <tr>
                <th scope="row" class="text-center">{{ $loop->index + 1 }}</th>
                <td>{{ $v->refname }}</td>
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
                    @if ($model->lock_course == 0)
                        <i data-url="{{ route('module.online.edit-userpoint-setting-module', [$model->id, $type, $v->id]) }}" data-item="{{ $v->id }}" data-course="{{ $model->id }}" style="cursor: pointer;" class="far fa-edit load-modal"></i> 
                        <i data-item="{{ $v->id }}" data-course="{{ $model->id }}" style="cursor: pointer;" class="fas fa-trash-alt remove-setting-item"></i>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Nhận điểm các nội dung khác --}}
<div>
    <h2 class="float-left">{{ trans('latraining.get_point_content_other') }}</h2>
</div>
<form id="frm_other" action="{{ route('module.online.userpoint-setting-others', ['course_id' => $model->id, 'type' => $type]) }}" method="post" class="form-ajax">
    <table class="table table-bordered">
        <thead>
            <tr class="bg-info">
                <th scope="col" class="text-center">#</th>
                <th scope="col" class="text-center">{{ trans('latraining.content') }}</th>
                <th scope="col" class="text-center">{{ trans('latraining.status') }}</th>
                <th scope="col" class="text-center">{{ trans('latraining.score') }}</th>
                <th scope="col" class="text-center">{{ trans('latraining.date_update') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($userpoint_others as $k => $v)
                @php
                    $updated_at = isset($setting_others[$v->ikey]) ? get_date($setting_others[$v->ikey]->updated_at, 'H:i:s d/m/Y') : '';
                    $point = isset($setting_others[$v->ikey]) ? $setting_others[$v->ikey]->pvalue : 0;
                @endphp
                <tr>
                    <th scope="row" class="text-center">{{ $loop->index + 1 }}</th>
                    <td>{{ $v->name }}</td>
                    <td class="text-center">
                        <select data-id="{{$k}}" name="promotion_status[]" class="form-control promotion-status">
                            <option value="0" @if(round($point,2) == 0) selected @endif>{{ trans('latraining.disable') }}</option>
                            <option value="1" @if(round($point,2) > 0) selected @endif>{{ trans('latraining.enable') }}</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <input type="text" class="is-number form-control format_float_number promotion-point text-center pp{{$k}}" name="userpoint_others[{{$v->ikey}}]" autocomplete="off" value="{{ is_null($point) ? '' : number_format($point, 2) }}" @if(round($point,2) == 0) readonly @endif  placeholder="{{ trans('latraining.score') }}">
                    </td>
                    <td class="text-center">{{ $updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-9"></div>
        <div class="col-md-3 text-right mt-2">
            @if ($model->lock_course == 0)
                <button type="submit" class="btn"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
            @endif
        </div>
    </div>
</form>