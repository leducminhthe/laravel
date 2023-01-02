@extends('themes.mobile.layouts.app')

@section('page_title', trans('latraining.note'))

@section('content')
    <div class="container-fluid suggest-container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="ibox-content suggest-container">
                    <div class="row mt-2">
                        <div class="col-12 col-xl-12 col-lg-12 col-md-12 text-right act-btns">
                            <div class="pull-right">
                                <button class="btn" id="create" onclick="noteMenu()">
                                    <i class="fa fa-edit"></i> Thêm ghi chú
                                </button>
{{--                                <button class="btn" id="delete-item">--}}
{{--                                    <i class="fa fa-trash"></i> {{ trans('labutton.delete') }}--}}
{{--                                </button>--}}
                            </div>
                        </div>
                    </div>
                    <p></p>
                    @foreach($notes as $note)
                        <div class="row note_{{ $note->id }}" style="align-items: center">
                            <div class="col-11" onclick="editNoteHandel({{ $note->id }})">
                                <h6 class="font-weight-bold mb-1">{{ $note->content }}</h6>
                                @if ($note->date_time)
                                    <p class="text-mute">
                                        {{ get_date($note->date_time, 'H:i - d/m/Y') }}
                                    </p>
                                @else
                                    <p class="text-mute">
                                        {{ get_date($note->created_at, 'd/m/Y') }}
                                    </p>
                                @endif
                            </div>
                            <div class="col-1 pl-0">
                                <i class="fa fa-trash" onclick="deleteNoteHandel({{ $note->id }})" style="font-size: 18px"></i>
                            </div>
                        </div>
                        <hr>
                    @endforeach
{{--                    <table class="tDefault table table-hover bootstrap-table text-nowrap">--}}
{{--                        <thead>--}}
{{--                            <tr>--}}
{{--                                <th data-field="check" data-checkbox="true"></th>--}}
{{--                                <th data-field="date_time" data-width="30%">{{ trans('lanote.created_at') }}</th>--}}
{{--                                <th data-field="content">{{ trans('lanote.note') }}</th>--}}
{{--                            </tr>--}}
{{--                        </thead>--}}
{{--                    </table>--}}
                </div>
            </div>
        </div>
    </div>

@endsection
@section('footer')
    <script type="text/javascript">
        function noteMenu(){
            $('#modal-create-note-mobile').modal();
        }

        function deleteNoteHandel(id) {
            var ids = [id];
            $.ajax({
                type: "POST",
                url: "{{ route('themes.mobile.note_mobile.remove') }}",
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'ids': ids
                },
                success: function (result) {
                    if (result.status === "success") {
                        window.location = '';
                        return false;
                    }
                    else {
                        window.location = '';
                        return false;
                    }
                }
            });
        }

        function editNoteHandel(id) {
            $.ajax({
                type: "POST",
                url: "{{ route('themes.mobile.note_mobile.edit') }}",
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': id
                },
                success: function (result) {
                    console.log(result);
                    $('#id_note').val(result.note.id);
                    $('#date_time').val(result.note.datetime)
                    $('#content_note').val(result.note.content)
                    $('#modal-create-note-mobile').modal();
                }
            });
        }
    </script>
@endsection
