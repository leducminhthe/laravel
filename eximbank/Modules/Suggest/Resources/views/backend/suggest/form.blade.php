@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.suggestion'),
                'url' => route('module.suggest')
            ],
            [
                'name' => $page_title,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <style>
        #list-comment .item{
            width: 100%;
            padding: 15px;
            margin-bottom: 10px;
            background: aliceblue;
        }
    </style>
<div role="main">
    <div class="clear"></div>
    <br>
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('lasuggest.info') }}</a></li>
        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane active">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-primary mb-3">
                            <div class="card-header text-white bg-primary">
                                {{ $profile->code . ' - ' . $profile->lastname . ' ' . $profile->firstname }}
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{ $model->content }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12" id="list-comment">
                                @if($comments)
                                    @foreach($comments as $comment)
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        {{ $profile->lastname . ' ' . $profile->firstname }} - {{ $profile->code }}
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="time-comment text-right">
                                                            <time>{{trans('lasuggest.time')}}: {{ get_date($comment->created_at, 'H:i:s d/m/Y') }}</time>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">{{ $comment->content }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @can('suggest-comment')
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <textarea class="form-control content" name="content" placeholder="{{ trans('lasuggest.comment_content') }}"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-9">
                                </div>
                                <div class="col-md-3 text-right">
                                    <button type="text" class="btn" id="add-comment">{{ trans('lasuggest.comment') }}</button>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var item = '';
    $('#add-comment').on('click', function () {
        var content = $('.content').val();

        $.ajax({
            url: "{{ route('module.suggest.save_comment', ['id' => $model->id]) }}",
            type: 'post',
            data: {
                content: content,
            },
        }).done(function(data) {
            $("textarea[name=content]").val('');

            item += '<div class="card mb-3">' +
                        '<div class="card-header">' +
                            '<div class="row">' +
                                '<div class="col-md-6">' + data.user + '</div>' +
                                '<div class="col-md-6">' +
                                    ' <div class="time-comment text-right">' +
                                        '<time> {{trans("lasuggest.time")}}: ' + data.created_at2 + '</time>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="card-body">' +
                            '<p class="card-text">' + data.content + '</p>' +
                        '</div>' +
                    '</div>';

            $("#list-comment").append(item);

            item = '';
            return false;
        }).fail(function(data) {
            show_message('{{ trans('laother.data_error') }}', 'error');
            return false;
        });
    });

</script>
@stop
