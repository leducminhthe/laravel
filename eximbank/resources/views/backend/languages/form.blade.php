@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.languages'),
                'url' => route('backend.languages')
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
    <div role="main">
        <form method="post" action="{{ route('backend.languages.save',$id) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <div class="row">
                <div class="col-md-8">

                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                        <a href="{{ route('backend.languages') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <br>
            <div class="tPanel">
                <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                    <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('lasetting.info') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div id="base" class="tab-pane active">

                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="pkey">{{ trans('lasetting.keyword') }}</label>
                            </div>
                            <div class="col-sm-9">
                                <input {{ $model->id ? 'readonly' : '' }} type="text" class="form-control" name="pkey" value="{{ $model->pkey }}" >
                            </div>
                        </div>
                        @foreach($lang_types as $type)
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="content">{{ $type->name }}</label>
                            </div>
                            <div class="col-sm-9">
                                @if($type->key == 'vi')
                                    <textarea class="form-control" name="content" rows="5">{{ $model->content }}</textarea>
                                @else
                                    <textarea class="form-control" name="content_{{ $type->key }}" rows="5">{{ $model->{'content_'.$type->key} }}</textarea>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="note">{{ trans('lacore.note') }}</label>
                            </div>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="note" rows="5">{{ $model->note}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


@stop
