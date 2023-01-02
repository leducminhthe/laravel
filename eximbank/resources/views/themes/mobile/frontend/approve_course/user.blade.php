@extends('themes.mobile.layouts.app')

@section('page_title', trans('lamenu.approve_register'))

@section('content')
    <div class="container mt-1">
        <div class="row">
            <div class="col-4 pl-0">
            </div>
            <div class="col-8 text-right">
                <div class="btn-group">
                    <button type="button" class="btn approve" data-status="1">
                        <i class="material-icons">done</i> @lang('app.approve')
                    </button>
                    <button type="button" class="btn approve" data-status="0">
                        <i class="material-icons">clear</i> @lang('app.deny')
                    </button>
                </div>
            </div>
        </div>
        <p></p>
        <div class="row">
            <div class="col">
                <table class="tDefault table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" width="5px"><input type="checkbox" id="check-all"></th>
                            <th class="text-center">@lang('app.info') ({{ $user_approved .'/'. $users->count() }})</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $key => $item)
                        <tr>
                            <td class="text-center"><input type="checkbox" name="btSelectItem" value="{{ $item->id }}"></td>
                            <td>
                                {{ $item->lastname . ' ' . $item->firstname .' ('. $item->code .')' }} <br>
                                {{ $item->title_name }} <br>
                                E: {{ $item->email }} <br>
                                @if($item->unit_status)
                                    @if($item->unit_status == 1)
                                        <span class="text-success">{{trans("backend.approve")}}</span>
                                    @elseif($item->unit_status == 2)
                                        <span class="text-warning">{{trans("backend.not_approved")}}</span>
                                    @else
                                        <span class="text-danger">{{trans("backend.deny")}}</span>
                                    @endif
                                @else
                                    <span class="text-warning">{{ trans("backend.not_approved") }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        $('#check-all').on('click', function () {
            if ($(this).is(':checked')){
                $("input[name=btSelectItem]").prop('checked', true);
            }else{
                $("input[name=btSelectItem]").prop('checked', false);
            }
        });

        $(".approve").on('click', function () {
            let status = $(this).data('status');
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 học viên', 'error');
                return false;
            }

            $.ajax({
                type: 'POST',
                url: '{{ route('themes.mobile.frontend.approve_course.approve', ['id' => $course->id, 'type' => $type]) }}',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'ids': ids,
                    'status': status
                }
            }).done(function(data) {
                window.location = '';
                return false;
            }).fail(function(data) {
                return false;
            });
        });
    </script>
@endsection
