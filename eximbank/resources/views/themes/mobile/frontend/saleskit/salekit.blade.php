@extends('themes.mobile.layouts.app')

@section('page_title', 'Sales kit')

@section('header')
    <style>
        #list-salekit .item-salekit a{
            color: #fff
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="sa4d25" id="list-salekit">
        <div class="row mt-2">
            @foreach ($category as $item)
                @php
                    $color = luminance($item->bg_mobile, 0.2);

                    $child = $check_child($item->id);
                    $route = $child ? route('themes.mobile.saleskit.salekit.child', ['cate_id' => $item->id]) : route('themes.mobile.saleskit.salekit.detail', ['cate_id' => $item->id]);
                @endphp
                <style>
                    .item_{{ $item->id }} {
                        background: linear-gradient(30deg, {{ $item->bg_mobile }}, {{ $color }});
                        padding: 5px;
                        border-radius: 10px;
                    }
                </style>

                <div class="col-6 col-md-3 p-1">
                    <div class="card text-center item-salekit shadow">
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ $route }}', 1, 2)" class="item_{{ $item->id }}">
                            <i class="fa fa-folder fa-5x"></i> <br>
                            {{ $item->name }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
