@extends('layouts.app')

@section('page_title', 'Salekit')

@section('header')
    <style>
        #list-salekit .item-salekit a{
            color: #4b5b6a
        }
        #list-salekit .item-cate-salekit a{
            color: #fff
        }
    </style>
@endsection

@section('content')
    <div class="sa4d25" id="list-salekit">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 p-0">
                    <div class="ibox-content forum-container">
                        <h2 class="st_title"><i class="uil uil-apps"></i>
                            <a href="{{ route('module.frontend.saleskit.salekit') }}">Salekit</a>
                            <i class="uil uil-angle-right"></i>
                            @if ($parent)
                                <a href="{{ route('module.frontend.saleskit.salekit_child', ['cate_id' => $parent->id]) }}">{{ $parent->name }}</a>
                                <i class="uil uil-angle-right"></i>
                            @endif
                            <span class="font-weight-bold">{{ $category->name }}</span>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                @foreach ($category_child as $item)
                    @php
                        $color = luminance($item->bg_mobile, 0.2);

                        $child = $check_child($item->id);
                        $route = $child ? route('module.frontend.saleskit.salekit_child', ['cate_id' => $item->id]) : route('module.frontend.saleskit.salekit.detail', ['cate_id' => $item->id]);
                    @endphp
                    <style>
                        .item_{{ $item->id }} {
                            background: linear-gradient(30deg, {{ $item->bg_mobile }}, {{ $color }});
                            padding: 5px;
                            border-radius: 5px;
                        }
                    </style>
                    <div class="col-12 col-md-3 p-1">
                        <div class="card text-center item-cate-salekit shadow rounded">
                            <a href="{{ $route }}" class="item_{{ $item->id }}">
                                <i class="fa fa-folder fa-5x"></i> <br>
                                {{ $item->name }}
                            </a>
                        </div>
                    </div>
                @endforeach
                @foreach ($saleskit as $item)
                    @php
                        $object = $saleskit_obj($item->id);
                    @endphp
                    @if ($object)
                    <div class="col-12 col-md-3 p-1">
                        <div class="card text-center item-salekit shadow rounded">
                            <a href="{{ route('module.saleskit.view_pdf', [$item->id]) }}" class="p-2">
                                <i class="fa fa-file fa-4x"></i> <br>
                                {{ $item->name }}
                            </a>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
