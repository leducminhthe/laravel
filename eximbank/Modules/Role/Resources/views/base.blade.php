<div role="main" id="rolepermission">
    <form method="post" action="{{route('backend.roles.save')}}" class="form-ajax">
        @csrf
        <input type="hidden" name="id" value="{{ $role->id }}">
    <div class="row">
        <div class="col-md-8">
        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                @can(['role-edit','role-create'])
                    <button class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;@lang('labutton.save')</button>
                @endcan
                <a href="{{ route('backend.roles') }}" class="btn"><i class="fa fa-times-circle"></i> @lang('labutton.cancel')</a>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <br>
        <div class="form-group required col-sm-12">
            <label>{{trans('backend.code')}}</label>
            <input type="text" name="code" value="{{ $role->code }}" class="form-control" {{ $role->id ? 'readonly' : '' }} required>
        </div>
         <div class="form-group required col-sm-12">
             <label>{{trans('backend.role_name')}}</label>
             <input type="text" name="name" value="{{ $role->name }}" class="form-control" {{ $role->id ? 'readonly' : '' }} required>
         </div>
        <div class="form-group required col-sm-12">
            <label>{{trans('latraining.description')}}</label>
            <input type="text" name="description" value="{{ $role->description }}" class="form-control" required>
        </div>
    </form>
</div>
