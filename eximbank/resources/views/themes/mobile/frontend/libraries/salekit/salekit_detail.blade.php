@extends('themes.mobile.layouts.app')

@section('page_title', $category->name)

@section('header')
    <style>
        #list-salekit .item-salekit a{
            color: #4b5b6a
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="sa4d25" id="list-salekit">
            <div class="row mt-2">
                @foreach ($libraries as $item)
                    @php
                        $object = $libraries_obj($item->id);
                    @endphp
                    @if ($object)
                        <div class="col-12 col-md-3 p-1">
                            <div class="card item-salekit shadow rounded">
                                <a href="{{ route('themes.mobile.libraries.view_pdf', [$item->id]) }}" class="row p-2">
                                    <div class="col-auto">
                                        <i class="fa fa-file-pdf fa-3x"></i>
                                    </div>
                                    <div class="col pl-0">
                                        <h6 class="mb-0">{{ $item->name }}</h6>
                                        <span class="small">{{ get_date($item->updated_at) }}</span>
                                        @if ($item->views > 0)
                                            <span class="float-right">
                                                <i class="fa fa-check"></i>
                                            </span>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
