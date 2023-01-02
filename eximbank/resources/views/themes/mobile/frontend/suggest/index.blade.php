@extends('themes.mobile.layouts.app')

@section('page_title', trans('lamenu.suggestion'))

@section('col_header_right')
    <span class="p-2" id="submit_suggest"><i class="fa fa-check" style="font-size: 1.5em"></i></span>
@endsection

@section('content')
    <div class="container-fluid suggest-container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                {{--  <div class="ibox-content suggest-container">
                    <div class="row mt-2">
                        <div class="col-12 col-xl-12 col-lg-12 col-md-12 text-right act-btns">
                            <button class="btn" id="create">
                                <i class="fa fa-edit"></i> {{ trans('app.create_suggest') }}
                            </button>
                        </div>
                    </div>
                    <p></p>
                    <table class="tDefault table table-hover bootstrap-table text-nowrap">
                        <thead>
                            <tr>
                                <th class="text-center">{{ trans('app.suggest') }}</th>
                                <th class="text-center">{{ trans('app.comment') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($suggest)
                            @foreach($suggest as $item)
                                <tr>
                                    <td>
                                        {{ $item->name }} <br>
                                        {{ get_date($item->created_at) }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('themes.mobile.suggest.get_comment', ['id' => $item->id]) }}"><i class="material-icons">comment</i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>  --}}
                <form action="{{ route('themes.mobile.suggest.save') }}" method="post" class="form-ajax w-100" id="form-suggest">
                    @csrf
                    <input type="hidden" name="id" value="">
                    <div class="form-group row mt-1">
                        <div class="col-md-12">
                            <input class="form-control" name="name" value="" required placeholder="{{ trans('app.name_suggest') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <textarea class="form-control" name="content" rows="5" style="border: none; height: 80vh;" placeholder="Nhập nội dung cần ghi"></textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
{{--  @section('modal')
    <div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">

        </div>
    </div>
@endsection  --}}
@section('footer')
    <script type="text/javascript">
        $('#submit_suggest').on('click', function(){
            $('#form-suggest').submit();
        });

        $('#create').on('click', function() {
            $('#modal-create').modal();
        });
    </script>
@endsection
