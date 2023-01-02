@extends('layouts.backend')

@section('page_title', $action)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('backend.role_management'),
                'url' => route('backend.roles')
            ],
            [
                'name' => $action,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    @php
        $tabs = request()->get('tabs', null);
    @endphp
<div role="main" id="rolepermission">
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            @if(isset($role->id))
                <li class="nav-item"><a href="#permission1" class="nav-link active" data-toggle="tab">{{ trans('backend.permission') }}</a></li>
            @endif
            <li class="nav-item"><a href="#base" class="nav-link  {{ isset($role->id) ? '' : 'active' }}" role="tab" data-toggle="tab">{{ trans('backend.role') }}</a></li>
        </ul>
        <div class="tab-content">
            @if(isset($role->id))
                <div id="permission1" class="tab-pane active">
                    @include('role::permission')
                </div>
            @endif
            <div id="base" class="tab-pane {{ isset($role->id) ? '' : 'active' }}">
                @include('role::base')
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('styles/module/role/js/role.js?v=1') }}"></script>
@stop
