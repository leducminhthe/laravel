@extends('layouts.app')

@section('page_title', 'Salekit')

@section('header')
    <style>
        #list-salekit .item-salekit a{
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
                            <span class="font-weight-bold">Sales Kit</span>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                @foreach ($category as $item)
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
                        <div class="card text-center item-salekit shadow rounded">
                            <a href="{{ $route }}" class="item_{{ $item->id }}">
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
